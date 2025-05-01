<?php

namespace App\Http\Controllers\Budgeting;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $department = Department::first();
        $purchases = Purchase::with('department')->paginate(5);
        $user = Auth::user();
        return view(".page.budgeting.management.PurchaseRequest.index", [
            'purchases' => $purchases,
            'department'=>$department,
            'userDepartment'=>$user->department->department_name ?? 'unknown',
            'currentDate'=>now()->format('j M y'),
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
        $validatedData = $this->validateRequest($request);

        $departmentId = auth()->user()->department_id;
        if (!$departmentId) {
            abort(403, 'User tidak memiliki department');
        }

        $purchases = $this->preparePurchaseData($validatedData, $departmentId);

        $purchases = $this->addBudgetNumbers($purchases);
        
        // Simpan menggunakan transaction
        DB::transaction(function () use ($purchases) {
            Purchase::insert($purchases);
        });
        
        return redirect()->route('PurchaseRequest.index')
               ->with('success', 'Data pembelian berhasil disimpan');
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'description' => 'required|array|min:1',
            'description.*' => 'required|string|max:100',
            'price' => 'required|array|min:1',
            'price.*' => 'required|string',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'remark' => 'nullable|array',
            'remark.*' => 'nullable|string|max:500'
        ]);
    }

    protected function preparePurchaseData(array $validatedData, $departmentId)
    {
        $purchases = [];
        
        foreach ($validatedData['description'] as $index => $desc) {
            $price = Purchase::parseRupiah($validatedData['price'][$index]);
            $quantity = $validatedData['quantity'][$index];
            
            $purchases[] = [
                'item_name' => $desc,
                'amount' => $price,
                'quanitity' => $quantity,
                'total_amount' => $price * $quantity,
                'remarks' => $validatedData['remark'][$index] ?? null,
                'department_id'=> $departmentId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        return $purchases;

    }
protected function addBudgetNumbers(array $purchases)
{
    $budgetNumbers = generateMultipleDocumentNumbers(count($purchases), 'SURAT', 'CAPEX');

    foreach ($purchases as $index => &$purchase) {
        $purchase['budget_no'] = $budgetNumbers[$index];
    }

    return $purchases;
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
}
