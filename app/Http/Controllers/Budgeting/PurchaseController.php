<?php

namespace App\Http\Controllers\Budgeting;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\sendApprovalRequest;
use App\Jobs\SendApprovedPurchase;
use App\Jobs\SendApprovedPurchaseNotification;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Budgeting\BudgetApproval;
use App\Models\Budgeting\BudgetApprover;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use App\Models\PurchaseDetail;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with('department')->paginate(5);
        $user = Auth::user();
        $departments = Department::all();
        $department= $user->department;
        $budget = BudgetAllocation::where('department_id', $user->department_id)->latest()->first();

        return view(".page.budgeting.management.PurchaseRequest.index", [
            'purchases' => $purchases,
            'department'=>$department,
            'userDepartment'=>$user->department->department_name ?? 'unknown',
            'currentDate'=>now()->format('j M y'),  
            'budgetNo' => $budget->budget_allocation_no ?? 'N/A',
            'departments'=>$departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $department = $user->department;
            $departmentId = $department->id;

            $validatedData = $request->validate([
            'description' => 'required|array|min:1',
            'description.*' => 'required|string|max:100',
            'price' => 'required|array|min:1',
            'price.*' => 'required|string',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'remark' => 'nullable|array',
            'remark.*' => 'nullable|string|max:500',
            'from_department' => 'nullable|string|exists:departments,id',
            'to_department' => 'nullable|string|exists:departments,id',
            'amount' => 'nullable|string|min:0',
            'reason' => 'nullable|string|max:250',
            ]);

        //* looping data & kalkulasi harga
        $grandTotal = 0;
        $purchases = [];
        $purchaseNumber = generateDocumentNumber();

        $master = Purchase::create([
            'department_id'=>$departmentId,
            'purchase_no'=>$purchaseNumber,
        ]);
        
        foreach ($validatedData['description'] as $index => $desc) {
            $price = max(0, Purchase::parseRupiah($validatedData['price'][$index]));
            $quantity = $validatedData['quantity'][$index];
            $total = $price * $quantity;
            $grandTotal += $total;

            // $isBalanceEnough = $department->balance >= $grandTotal;

            $purchases[] = [
                'purchase_no'=> $master->purchase_no,
                'item_name' => $desc,
                'amount' => $price,
                'quanitity' => $quantity,
                'total_amount' => $total,
                'remarks' => $validatedData['remark'][$index] ?? null,
                // 'status' => $isBalanceEnough ? 'approved' : 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // dd($purchases, $grandTotal);
        PurchaseDetail::insert($purchases);

        $master-> update([
            'grand_total'=>$grandTotal,
        ]);
        // dd($master);

        $amount = Purchase::parseRupiah($validatedData['amount']);
        // dd($grandTotal,$department->balance);

        //* kondisi balance kurang
        if ($grandTotal > $department->balance) {
            if (
                $validatedData['from_department'] &&
                $validatedData['to_department'] &&
                $validatedData['amount'] &&
                $validatedData['reason']
            ) {
                $toDept = Department::findorfail($validatedData['to_department']);
                // dd($toDept, $toDept->balance, $department->balance);
                if ($toDept->balance < $amount) {
                    DB::rollback();
                    Alert::toast("The selected department's budget is insufficient.", 'error');
                    return back();
                }

                $master->update([
                    'status'=>'pending'
                ]);

                $budgetReqNo = $this->getBudgetRequestNo($validatedData['from_department']);
                $budgetRequest = BudgetRequest::create([
                    'budget_req_no' => $budgetReqNo,
                    'from_department_id' => $validatedData['from_department'],
                    'to_department_id' => $validatedData['to_department'],
                    'budget_purchase_no' => $purchaseNumber,
                    'amount' => Purchase::parseRupiah($validatedData['amount']),
                    'reason' => $validatedData['reason'],
                    'status'=> 'pending',
                ]);

                $approver= null;
                $approver= BudgetApprover::where('department_id', $validatedData['to_department'])
                                        ->first();
                $budgetApproval= BudgetApproval::create([
                'budget_req_no' => $budgetRequest->budget_req_no,
                'nik' => $approver->nik,
                'status' => 'pending',
                'token' => Str::uuid()
            ]);
            if($approver && $approver->user){
                $approver = $approver->user;
            }
                if($approver && $approver->email){
                    $requestData=[
                        'to_department_name'=> $toDept->department_name,
                        'from_department_name'=>$department->department_name,
                        'budget_purchase_no'=>$purchaseNumber,
                        'amount'=>$validatedData['amount'],
                        'reason'=>$validatedData['reason']
                    ];
                    sendApprovalRequest::dispatch($approver, $requestData, $budgetApproval);
                }
                DB::commit();
                Alert::toast('Saldo tidak mencukupi. Permintaan budget telah diajukan.', 'info');
                return back();
            }
            //* rollback jika input table request ada yang kosong
            else{
                DB::rollback();
                Alert::toast('lengkapi semua data yang diminta. Permintaan budget gagal diajukan.', 'info');
                return back();
            }
        }

        
        // if($master){
            
        //     SendApprovedPurchaseNotification::dispatch($admin, $mailData, $department);
        // }else{

        // }
    
        $department->withdraw($grandTotal);
        DB::commit();
        Alert::success('Berhasil', 'Data pembelian berhasil disimpan.');
        return redirect()->route('PurchaseRequest.index');

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //* email diapprove
    public function approved(Request $request)
    {
        return $this->handleApprovalStatusUpdate($request, 'approve');
    }

    public function rejected(Request $request)
    {
        return $this->handleApprovalStatusUpdate($request, 'reject');
    }

    private function handleApprovalStatusUpdate(Request $request, $expectedStatus)
    {
        $budget_no = $request->query('budget_req_no');
        $status = $request->query('status');
        $token = $request->query('token');
        $nik = $request->query('nik');

        // Validasi awal status dan URL
        if (!in_array($status, ['approve', 'reject'])) {
            return response()->view('emails.invalidStatus', [], 400);
        }

        if ($status !== $expectedStatus) {
            return response()->view('emails.invalidStatus', [], 400);
        }

        DB::beginTransaction();
        try {
            $requestApprove = BudgetApproval::where('budget_req_no', $budget_no)
                ->where('nik', $nik)
                ->where('token', $token)->first();

            if (!$requestApprove) {
                return response()->view('emails.alreadyprocessed', [], 404); // Tidak ditemukan
            }

            if ($requestApprove->status !== 'pending') {
                return response()->view('emails.alreadyprocessed', [], 400);
            }

        if ($status === 'approve') {
            // Langsung simpan dan tampilkan konfirmasi
            $requestApprove->status = 'approve';
            $requestApprove->token = null;
            $requestApprove->updated_at = now();
            $requestApprove->save();

            //* edit status purchase dan budgetrequest
            $budgetRequest = BudgetRequest::where('budget_req_no', $budget_no)->first();
            if ($budgetRequest) {
                $toDept =$budgetRequest->fromDepartment;
                $fromDept = $budgetRequest->toDepartment;
                $amount = $budgetRequest->amount;

                // Transfer saldo antar wallet
                $fromDept->transfer($toDept, $amount);
                $toDept->withdraw($toDept->balanceInt);

                $budgetRequest->status = 'approved';
                $budgetRequest->save();

                $purchase = Purchase::where('purchase_no', $budgetRequest->budget_purchase_no)->first();
                if($purchase){
                    Purchase::where('purchase_no', $budgetRequest->budget_purchase_no)
                    ->update(['status' => 'approved']);

                    $admin = User::where('username', 'admin')->first();
                    if($admin){
                        $mailData=[
                            'from_department'=> $fromDept,
                            'to_department'=>$toDept,
                            'balance'=>$toDept->balanceInt,
                            'amount'=>$amount
                        ];
                        // dd($mailData);
                        SendApprovedPurchase::dispatch($admin, $mailData, $budgetRequest, $purchase);
                    }
                }
            }
            DB::commit();
            return view('emails.finishProcces');
        }else{
            DB::commit();
                return view('emails.rejectForm', [
                    'budget_req_no' => $budget_no,
                    'nik' => $nik
                ]);
        }
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    //* fungsi jika direject di email
    public function submitRejectFeedback(Request $request)
    {
        $request->validate([
            'budget_req_no' => 'required',
            'nik' => 'required',
            'feedback' => 'required|string|max:1000',
        ]);

        try {
            $data = BudgetApproval::where('budget_req_no', $request->budget_req_no)
                ->where('nik', $request->nik)
                ->first();

            if (!$data || $data->status !== 'pending') {
                return response()->view('emails.alreadyprocessed', [], 400);
            }

            //* update table budgetapproval
            $data->status = 'reject';
            $data->token = null;
            $data->feedback = $request->feedback;
            $data->updated_at = now();
            $data->save();

            //*update budgetrequest dan pruchase
            $budgetRequest = BudgetRequest::where('budget_req_no', $request->budget_req_no)->first();
                if ($budgetRequest) {
                    $budgetRequest->status = 'rejected';
                    $budgetRequest->save();

                    Purchase::where('purchase_no', $budgetRequest->budget_purchase_no)
                        ->update(['status' => 'rejected']);
                }
                    return view('emails.finishProcces');
                } catch (\Exception $e) {
                    return back()->with('error', 'Gagal menyimpan alasan: ' . $e->getMessage());
                }
            }

    protected function getBudgetRequestNo($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        $departmentCode = str_replace(" ","", strtoupper(substr($department->department_name, 0, 3)));

        $lastAllocation = BudgetRequest::where('budget_req_no', 'like', 'REQCAPEX/'.$departmentCode.'/%')
                                        ->latest()
                                        ->first();

        $lastNumber = $lastAllocation ? (int) substr($lastAllocation->budget_req_no, -4) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "REQCAPEX/{$departmentCode}/{$newNumber}";
    }
}
