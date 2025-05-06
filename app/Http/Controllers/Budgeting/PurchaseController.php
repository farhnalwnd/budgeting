<?php

namespace App\Http\Controllers\Budgeting;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
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
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($grandTotal > $department->balance) {
            if (
                
                $validatedData['from_department'] &&
                $validatedData['to_department'] &&
                $validatedData['amount'] &&
                $validatedData['reason']
            ) {
                $budgetReqNo = $this->getBudgetRequestNo($validatedData['from_department']);

                BudgetRequest::create([
                    'budget_req_no' => $budgetReqNo,
                    'from_department_id' => $validatedData['from_department'],
                    'to_department_id' => $validatedData['to_department'],
                    'amount' => Purchase::parseRupiah($validatedData['amount']),
                    'reason' => $validatedData['reason'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            Alert::toast('Saldo tidak mencukupi. Permintaan budget telah diajukan.', 'info');
            return back();
        }

        $department->withdraw($grandTotal);
        Purchase::insert($purchases);

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

//     $validatedData = $this->validateRequest($request);
//     $department = auth()->user()->department;
//     $walletBalance = $department->balance;

//     // Hitung grand total
//     $grandTotal = $this->priceStatus($validatedData['price'], $validatedData['quantity']);
//     if($grandTotal>$walletBalance){
//         return back()->with('error','saldo tidak mencukupi');
//     }

//     // Siapkan data + nomor budget
//     $purchases = $this->preparePurchaseData($validatedData, $department->id);
//     $purchases = $this->addBudgetNumbers($purchases);

//     // Eksekusi dalam transaksi
//     try {
//         DB::transaction(function () use ($purchases, $department, $grandTotal) {
//             $department->withdraw($grandTotal); // Kurangi saldo
//             Purchase::insert($purchases); // Simpan data
//         });
//     } catch (\Exception $e) {
//         return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
//     }

//     return redirect()->route('PurchaseRequest.index')
//            ->with('success', 'Data pembelian berhasil disimpan');
// }

//     protected function validateRequest(Request $request)
//     {
//         return $request->validate([
//             'description' => 'required|array|min:1',
//             'description.*' => 'required|string|max:100',
//             'price' => 'required|array|min:1',
//             'price.*' => 'required|string',
//             'quantity' => 'required|array|min:1',
//             'quantity.*' => 'required|integer|min:1',
//             'remark' => 'nullable|array',
//             'remark.*' => 'nullable|string|max:500'
//         ]);
//     }

//     protected function preparePurchaseData(array $validatedData, $departmentId)
//     {
//         $purchases = [];
        
//         foreach ($validatedData['description'] as $index => $desc) {
//             $price = Purchase::parseRupiah($validatedData['price'][$index]);
//             $quantity = $validatedData['quantity'][$index];
            
//             $purchases[] = [
//                 'item_name' => $desc,
//                 'amount' => $price,
//                 'quanitity' => $quantity,
//                 'total_amount' => $price * $quantity,
//                 'remarks' => $validatedData['remark'][$index] ?? null,
//                 'department_id'=> $departmentId,
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ];
//         }
//         return $purchases;

//     }
// protected function addBudgetNumbers(array $purchases)
// {
//     $budgetNumbers = generateMultipleDocumentNumbers(count($purchases), 'SURAT', 'CAPEX');

//     foreach ($purchases as $index => &$purchase) {
//         $purchase['budget_no'] = $budgetNumbers[$index];
//     }

//     return $purchases;
// }

// protected function priceStatus(array $prices, array $quantities){
//         $grandTotal = 0;
//     foreach ($prices as $index => $priceStr) {
//         $price = max(0, Purchase::parseRupiah($priceStr)); // Hindari nilai negatif
//         $quantity = $quantities[$index];
//         $grandTotal += $price * $quantity;
//     }
// return $grandTotal;
// }

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
