<?php

namespace App\Http\Controllers\Budgeting;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use Illuminate\Auth\Events\Validated;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

        $grandTotal = 0;
        $purchases = [];
        $budgetNumbers = generateMultipleDocumentNumbers(count($validatedData['description']));
        $isBalanceEnough = $department->balance >= $grandTotal;
        foreach ($validatedData['description'] as $index => $desc) {
            $price = max(0, Purchase::parseRupiah($validatedData['price'][$index]));
            $quantity = $validatedData['quantity'][$index];
            $total = $price * $quantity;
            $grandTotal += $total;
            
            $purchases[] = [
                'item_name' => $desc,
                'amount' => $price,
                'quanitity' => $quantity,
                'total_amount' => $total,
                'remarks' => $validatedData['remark'][$index] ?? null,
                'department_id' => $departmentId,
                'purchase_no' => $budgetNumbers[$index],
                'status' => $isBalanceEnough ? 'approved' : 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $isBalanceEnough = $department->balance >= $grandTotal;
        $purchases = array_map(function ($item) use ($isBalanceEnough) {
            $item['status'] = $isBalanceEnough ? 'approved' : 'pending';
            return $item;
        }, $purchases);
    
        Purchase::insert($purchases);

        $amount = Purchase::parseRupiah($validatedData['amount']);
        if ($grandTotal > $department->balance) {
            if (
                $validatedData['from_department'] &&
                $validatedData['to_department'] &&
                $validatedData['amount'] &&
                $validatedData['reason']
            ) {
                $toDept = Department::findorfail($validatedData['to_department']);
                if($toDept->balance < $amount){
                    DB::rollback();
                    Alert::toast("The selected department's budget is insufficient.", 'error');
                    return back();
                }
                $budgetReqNo = $this->getBudgetRequestNo($validatedData['from_department']);

                BudgetRequest::create([
                    'budget_req_no' => $budgetReqNo,
                    'from_department_id' => $validatedData['from_department'],
                    'to_department_id' => $validatedData['to_department'],
                    'budget_purchase_no' => $budgetNumbers[$index],
                    'amount' => Purchase::parseRupiah($validatedData['amount']),
                    'reason' => $validatedData['reason'],
                    'status'=> 'pending',
                ]);

                DB::commit();
                // dd($budgetReqNo);
                Alert::toast('Saldo tidak mencukupi. Permintaan budget telah diajukan.', 'info');
                return back();
            }
            else{
                DB::rollback();
                Alert::toast('Saldo tidak mencukupi. Permintaan budget gagal diajukan.', 'info');
                return back();
            }
        }

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
