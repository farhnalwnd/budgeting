<?php

namespace App\Http\Controllers\Budgeting;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Jobs\sendApprovalRequest;
use App\Jobs\SendApprovedPurchase;
use App\Jobs\SendApprovedPurchaseNotification;
use App\Jobs\sendLessBalance;
use App\Jobs\SendRejectedPurchaseNotification;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Budgeting\BudgetApproval;
use App\Models\Budgeting\BudgetApprover;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\CategoryMaster;
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
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $departments = Department::all();
        $department = $user->department;
        $budget = BudgetAllocation::where('department_id', $user->department_id)->latest()->first();
        $budgetNo = $budget->budget_allocation_no ?? 'N/A';
        $currentDate = now()->format('j M y');

        return view(".page.budgeting.management.PurchaseRequest.index", compact(
            'user',
            'department',
            'departments',
            'budget',
            'budgetNo',
            'currentDate'
        ));
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
            'UM' => 'required|array|min:1',
            'UM.*' => 'required|string|max:5',
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

            $purchases[] = [
                'purchase_no'=> $master->purchase_no,
                'item_name' => $desc,
                'UM' => $validatedData['UM'][$index] ?? null,
                'amount' => $price,
                'quantity' => $quantity,
                'total_amount' => $total,
                'remarks' => $validatedData['remark'][$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // dd($purchases, $grandTotal);
        PurchaseDetail::insert($purchases);

        $master-> update([
            'grand_total'=>$grandTotal,
        ]);

        $amount = Purchase::parseRupiah($validatedData['amount'] ?? 0);

        //* kondisi balance kurang
        if ($grandTotal > $department->balanceForYear(now()->year)) {
            if (
                $validatedData['from_department'] &&
                $validatedData['to_department'] &&
                $validatedData['amount'] &&
                $validatedData['reason']
            ) {
                $toDept = Department::findorfail($validatedData['to_department']);
                if ($toDept->balanceForYear(now()->year) < $amount) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'Depertement yang dituju tidak memiliki dana yang mencukupi'
                    ], 422);
                }

                $master->update([
                    'status'=>'pending'
                ]);

                $budgetReqNo = $this->getBudgetRequestNo($validatedData['from_department']);
                $budgetRequest = BudgetRequest::create([
                    'budget_req_no' => $budgetReqNo,
                    'nik' => $user->nik,
                    'from_department_id' => $validatedData['from_department'],
                    'to_department_id' => $validatedData['to_department'],
                    'budget_purchase_no' => $purchaseNumber,
                    'amount' => Purchase::parseRupiah($validatedData['amount']),
                    'reason' => $validatedData['reason'],
                    'status'=> 'pending',
                ]);

                $approver= null;
                $approver= BudgetApprover::where('department_id', $validatedData['to_department'])->first();
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
                        'budget_req_no'=>$budgetRequest->budget_req_no,
                        'amount'=>$validatedData['amount'],
                        'reason'=>$validatedData['reason']
                    ];
                    sendApprovalRequest::dispatch($approver, $requestData, $budgetApproval);
                }

                //*log activity on budgetRequest
                activity()
                    ->performedOn($budgetRequest)
                    ->inLog('budget-request')
                    ->event('create')
                    ->causedBy($user)
                    ->withProperties([
                        'no' => $budgetRequest->budget_req_no,
                        'action' => 'create',
                        'data' => [
                            'department_id' => $departmentId,
                            'to_department' => $toDept->department_name,
                            'purchase_no' => $purchaseNumber,
                            'status' => $budgetRequest->status
                        ]
                    ])
                    ->log('create budget-request ' . $budgetRequest->budget_req_no . ' by ' . $user->name . ' at ' . now());
                DB::commit();
                return response()->json([
                        'success' => true,
                        'pending' => true,
                        'new_balance' => $department->fresh()->balanceForYear(now()->year),
                        'message' => 'Saldo tidak mencukupi. Permintaan budget telah diajukan.',
                    ], 200);
                }
            //* rollback jika input table request ada yang kosong
            else{
                    DB::rollback();
                    return response()->json([
                    'success' => false,
                    'message' => "lengkapi semua data"
                ], 422);
            }
        }

        // $department->withdraw($grandTotal);
        $department->withdrawFromYear(now()->year, $grandTotal);
            // dd($department);

            //* email ke user
        $data = purchase::with(['detail', 'department'])->where('purchase_no', $purchaseNumber)->firstOrFail();
        $purchaseDetails = $data->detail;
        // ! ganti admin menjadi nik
        $admin = user::where('username', 'admin')->first();
        $users = user::where('department_id', $departmentId)->first();
        SendApprovedPurchaseNotification::dispatch($users, $data, $purchaseDetails, false);
        SendApprovedPurchaseNotification::dispatch($admin, $data->fresh(), $purchaseDetails->fresh(), true);


        //*log activity on purchaseRequest
            activity()
                ->performedOn($data)
                ->inLog('purchase-request')
                ->event('create')
                ->causedBy($user)
                ->withProperties(['no'=> $data->purchase_no, 'action'=>'create',
                'data'=>[
                    'department_id'=> $departmentId,
                    'category_id'=> $data->category_id ?? null,
                    'grand_total'=> $data->grand_total,
                    'status'=> $data->status
                ]
                ])
                ->log('create purchase-request '. $data->purchase_no . ' by'. $user->name . ' at: ' . now());
            DB::commit();
        return response()->json([
                'new_balance' => $department->fresh()->balanceForYear(now()->year),
                'success' => true,
                'pending' => false,
                'message' => 'Data berhasil disimpan!'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
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
        $purchase = Purchase::findOrfail($id);
        $categories = CategoryMaster::all();
        $departments = Department::all();
        $dept = Department::where('id', $purchase->department_id)->first();
        $deptId = $dept->id;
        return view('page.budgeting.management.PurchaseRequest.edit', compact('purchase','categories','dept','deptId', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, string $id)
    {
        DB::beginTransaction();
    
        try {
            $user = auth()->user();
            $now = now()->year;

            $validated = $request->validated();
    
            $purchase = Purchase::findOrFail($id);
            $fromDept = Department::findOrFail($validated['fromDept']);
    
            $oldAmount = $purchase->actual_amount;
            $grandTotal = $purchase->grand_total;
            $newActualAmount = $validated['actual_amount'];
    
            //* actual amount pernah diinput
            if ($oldAmount !== null) {
                if ($oldAmount > $grandTotal) {
                    // $fromDept->deposit($oldAmount - $grandTotal);
                    $fromDept->depositToYear(now()->year, $oldAmount - $grandTotal);
                } elseif ($oldAmount < $grandTotal) {
                    // $fromDept->withdraw($grandTotal - $oldAmount);
                    $fromDept->withdrawFromYear(now()->year, $grandTotal - $oldAmount);
                }
            }
    
            $purchase->update([
                'PO' => $validated['PO'],
                'category_id' => $validated['category_id'],
                'actual_amount' => $newActualAmount
            ]);
    
            // * ketika actual amount baru  besar dari grand total
            if ($newActualAmount > $grandTotal) {
                $diff = $newActualAmount - $grandTotal;
    
                // * ketika balance lebih kecil dari diff
                if ($fromDept->balanceForYear(now()->year) < $diff) {
                    $toDept = Department::findOrFail($validated['department_id']);
    
                    if ($toDept->balanceForYear(now()->year) < $diff-$fromDept->balanceForYear(now()->year)) {
                        DB::rollBack();
                        Alert::toast("The selected department's budget is insufficient.", 'error');
                        return redirect()->route('purchase-request.index');
                    }
                    //*balance pertahun
                    $toDept->transferForYear($fromDept, $now, $diff-$fromDept->balance);
                }
    
                // $fromDept->withdraw($diff);
                $fromDept->withdrawFromYear(now()->year, $diff);
    
            }//* ketika new actual amount kecil dari grand total
            elseif ($newActualAmount < $grandTotal) {
                // $fromDept->deposit($grandTotal - $newActualAmount);
                $fromDept->depositToYear(now()->year, $grandTotal - $newActualAmount);
            }


            //* activity log uppdate
            activity()
                ->performedOn($purchase)
                ->inLog('purchase-update')
                ->event('update')
                ->causedBy($user)
                ->withProperties([
                    'no' => $purchase->purchase_no,
                    'action' => 'update',
                    'data' => [
                        'PO' => $purchase->PO,
                        'actual_amount' => $purchase->actual_amount,
                        'category' => $purchase->category->name,
                    ]
                ])
                ->log('purchase-update ' .  $purchase->purchase_no . ' by ' . $user->name . ' at: ' . now());

            DB::commit();
            return redirect()->route('purchase-request.index')->with('success', 'Purchase request berhasil diperbarui!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $purchase_no)
    {
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
            $budgetRequest = BudgetRequest::with(['toDepartment', 'fromDepartment'])->where('budget_req_no', $budget_no)->first();
            if ($budgetRequest) {
                $toDept =$budgetRequest->fromDepartment;
                $fromDept = $budgetRequest->toDepartment;
                $amount = $budgetRequest->amount;
                $purchases = $budgetRequest->purchase;
                $admin = user::where('username', 'admin')->first();
                $user = user::where('nik', $budgetRequest->nik)->first();
                $now = now()->year;

                // Transfer saldo antar wallet
                // $fromDept->transfer($toDept, $amount);
                $fromDept->transferForYear($toDept, $now,  $amount);
                $actualBalance = $toDept->balanceForYear($now);

                if($actualBalance < $purchases->grand_total){
                    $purchases->update(['status' => 'rejected']);
                        $budgetRequest->status = 'approved';
                        $budgetRequest->feedback = 'saldo anda tetap kurang karna telah digunakan untuk yang lain, buat purchase baru!!';
                        $budgetRequest->save();

                        $toDept->transferForYear($fromDept, $now, $amount);
                        sendLessBalance::dispatch($user, $purchases, $budgetRequest);
                    }else{
                // $toDept->withdraw($toDept->balanceInt);
                $toDept->withdrawFromYear($now, $actualBalance);

                $budgetRequest->status = 'approved';
                $budgetRequest->save();

                    if($purchases){
                        $purchases->update(['status'=> 'approved']);

                        $purchaseDetails = $purchases->detail;
                        $toDeptName = $toDept->department_name;
                        $fromDeptName = $fromDept->department_name;
                        $deptName = [$toDeptName,$fromDeptName];
                        //! ganti sama nik dari admin
                        SendApprovedPurchase::dispatch($user, $purchases , $budgetRequest, $deptName, $purchaseDetails, false);
                        SendApprovedPurchase::dispatch($admin, $purchases , $budgetRequest, $deptName, $purchaseDetails, true);
                    }
                }
            }
                $approver = User::where('nik', $requestApprove->nik)->first();



                //* activity log approve
                activity()
                    ->performedOn($budgetRequest)
                    ->inLog('budget-request approval')
                    ->event('approve')
                    ->causedBy($approver)
                    ->withProperties([
                        'no' => $budgetRequest->budget_req_no,
                        'action' => 'approval',
                        'data' => [
                            'feedback' => '-'
                        ]
                    ])
                    ->log('budget-request approval ' .  $budgetRequest . ' by ' . $approver . ' at: ' . now());
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
            Log::error('Terjadi kesalahan: ' . $e->getMessage());
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

        DB::beginTransaction();

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

            //* update budgetrequest dan purchase
            $budgetRequest = BudgetRequest::with(['purchase', 'toDepartment', 'fromDepartment'])
                ->where('budget_req_no', $request->budget_req_no)
                ->first();

            if ($budgetRequest) {
                $budgetRequest->status = 'rejected';
                $budgetRequest->feedback = $data->feedback;
                $budgetRequest->save();

                $purchases = $budgetRequest->purchase;
                if ($purchases) {
                    $purchases->update(['status' => 'rejected']);

                    $purchaseDetails = $purchases->detail;
                    $toDept = $budgetRequest->toDepartment->department_name;
                    $fromDept = $budgetRequest->fromDepartment->department_name;
                    $deptName = [$toDept, $fromDept];
                    $user = user::where('nik', $budgetRequest->nik)->first();

                    //* Kirim notifikasi dalam transaction-safe context
                    SendRejectedPurchaseNotification::dispatch($user, $purchases, $budgetRequest, $deptName, $purchaseDetails);
                }
            }

            //* log untuk reject budget request
            activity()
                ->performedOn($budgetRequest)
                ->inLog('budget-request reject')
                ->event('reject')
                ->causedBy($data->user)
                ->withProperties([
                    'no' => $budgetRequest->budget_purchase_no,
                    'action' => 'reject',
                    'data' => [
                        'feedback' => $data->feedback,
                    ]
                ])
                ->log('budget-request reject ' .  $budgetRequest->budget_purchase_no . ' by ' . $data->user->name . ' with feedback '. $data->feedback . ' at: ' . now());
            DB::commit(); 
            return view('emails.finishProcces');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan alasan: ' . $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $query = Purchase::with(['department', 'detail', 'category', 'budgetRequest']);

        if ($user->username !== 'admin') {
            $query->where('department_id', $user->department_id);
        }
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function getYear()
    {
        $years = Purchase::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
        return response()->json($years);
    }
    public function getBalanceByYear(Request $request)
    {
        $year = $request->query('year');
        $user = auth()->user();
        $department = $user->department;

        $balance = $department->balanceForYear($year);

        return response()->json(['new_balance' => $balance]);
    }


    protected function getBudgetRequestNo($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        $departmentCode = str_replace(" ","", strtoupper(substr($department->department_name, 0, 3)));
        $year = now()->format('y');
        // Cari alokasi terakhir yang dimulai dengan CAPEX/{kodeDepartemen}/{tahun}
        $lastAllocation = BudgetRequest::where('budget_req_no', 'like', 'CAPEX/REQ/'.$departmentCode.'/'.$year.'/%')
                                        ->latest()
                                        ->first();
                                        
        // Ambil angka urutan terakhir dari nomor alokasi
        $lastNumber = $lastAllocation ? (int) substr($lastAllocation->budget_req_no, -4) : 0;

        // Menambahkan 1 dan memastikan nomor urut 4 digit
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Menghasilkan nomor alokasi baru
        return "CAPEX/REQ/{$departmentCode}/{$year}/{$newNumber}";
    }
}
