<?php

namespace App\Http\Controllers\Budgeting;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Budgeting\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::all();
        return view(".page.budgeting.management.PurchaseRequest.index", ['purchases' => $purchases]);
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
        
        $purchases = $this->preparePurchaseData($validatedData);
        
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

    protected function preparePurchaseData(array $validatedData)
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
                'created_at' => now(),
                'updated_at' => now()
            ];
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
