<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Budgeting\BudgetList;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('page.budgeting.management.budget-list.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $validatedData = $request->validate([
                'no' => 'required|exists:budget_allocations,budget_allocation_no',
                'category' => 'required|exists:category_masters,id',
                'name' => 'required|array|min:1',
                'quantity' => 'required|array|min:1',
                'um' => 'required|array|min:1',
                'amount' => 'required|array|min:1',
                'total' => 'required|array|min:1',
                'name.*' => 'required|string|max:255',
                'quantity.*' => 'required|integer|min:1',
                'um.*' => 'required|string|max:255',
                'amount.*' => 'required|string|min:0',
                'total.*' => 'required|string|min:0'
            ]);

            // dd('bisa ',$request);
            $budget = null;
            $user = Auth::user();
            foreach($validatedData['name'] as $index => $name)
            {
                $amount =  max(0,Purchase::parseRupiah($validatedData['amount'][$index]));
                $budget = BudgetList::create([
                    'budget_allocation_no' => $validatedData['no'],
                    'name' => $validatedData['name'][$index],
                    'category_id' => $validatedData['category'],
                    'quantity' => $validatedData['quantity'][$index],
                    'um' => $validatedData['um'][$index],
                    'default_amount' => $amount,
                    'total_amount' => $amount * $validatedData['quantity'][$index]
                ]);
            }
            
            
            $result = $this->calculateBudget($budget->budget_allocation_no);
            if($result instanceof \Exception){
                throw new \Exception("Failed to calculate budget.");
            }

            activity()
                ->performedOn($budget)
                ->inLog('budget-list')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'create'])
                // 'data' => [
                //     'budget_allocation_no' => $budget->budget_allocation_no,
                //     'name' => $budget->budget_allocation_no,
                //     'category_id' => $budget->category_id,
                //     'quantity' => $budget->quantity,
                //     'um' => $budget->um,
                //     'default_amount' => $budget->default_amount,
                //     'total_amount' => $budget->total_amount
                // ]
                ->log('Create budget-list ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-list successfully created!', 'success');
            return redirect()->route('budget-list.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error creating the budget-list. '.$e->getMessage(), 'error');
            return back();
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
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $validatedData = $request->validate([
                'no' => 'required|exists:budget_allocations,budget_allocation_no',
                'name' => 'required|string|max:255',
                'category' => 'required|exists:category_masters,id',
                'quantity' => 'required|numeric',
                'um' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'total' => 'required|numeric'
            ]);

            $user = Auth::user();
            $budget = BudgetList::findOrFail($id);
            $budgetOld = clone $budget; //simpan no budget yang lama

            $budget->update([
                'budget_allocation_no' => $validatedData['no'],
                'name' => $validatedData['name'],
                'category_id' => $validatedData['category'],
                'quantity' => $validatedData['quantity'],
                'um' => $validatedData['um'],
                'default_amount' => $validatedData['amount'],
                'total_amount' => $validatedData['total']
            ]);

            // kalau no budget berubah
            if($budgetOld->budget_allocation_no !== $budget->budget_allocation_no){
                $this->calculateBudget($budgetOld->budget_allocation_no);
            }

            $result = $this->calculateBudget($budget->budget_allocation_no);
            if($result instanceof \Exception){
                throw new \Exception("Failed to calculate budget.");
            }

            activity()
                ->performedOn($budget)
                ->inLog('budget-list')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'update', 
                'oldData' => [
                    'budget_allocation_no' => $budgetOld->budget_allocation_no,
                    'name' => $budgetOld->budget_allocation_no,
                    'category_id' => $budgetOld->category_id,
                    'quantity' => $budgetOld->quantity,
                    'um' => $budgetOld->um,
                    'default_amount' => $budgetOld->default_amount,
                    'total_amount' => $budgetOld->total_amount
                ],
                'newData' => [
                    'budget_allocation_no' => $budget->budget_allocation_no,
                    'name' => $budget->budget_allocation_no,
                    'category_id' => $budget->category_id,
                    'quantity' => $budget->quantity,
                    'um' => $budget->um,
                    'default_amount' => $budget->default_amount,
                    'total_amount' => $budget->total_amount
                ]])
                ->log('Update budget-list ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-list successfully updated!', 'success');
            return redirect()->route('budget-list.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error updating the budget-list. '.$e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $user = Auth::user();

            $budget = BudgetList::findOrFail($id);
            $budget->delete();
            
            $result = $this->calculateBudget($budget->budget_allocation_no);
            if($result instanceof \Exception){
                throw new \Exception("Failed to calculate budget.");
            }
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-list')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'delete',
                'data' => [
                    'budget_allocation_no' => $budget->budget_allocation_no,
                    'name' => $budget->budget_allocation_no,
                    'category_id' => $budget->category_id,
                    'quantity' => $budget->quantity,
                    'um' => $budget->um,
                    'default_amount' => $budget->default_amount,
                    'total_amount' => $budget->total_amount
                ]])
                ->log('Delete budget-list ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-list successfully deleted!', 'success');
            return redirect()->route('budget-list.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error deleting the budget-list. '.$e->getMessage(), 'error');
            return back();
        }
    }

    public function getBudgetList(){
        $budgets = BudgetList::with('category')->get();
        return response()->json($budgets);
    }

    public function calculateBudget($no){
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $totalAmount = BudgetList::where('budget_allocation_no', $no)->sum('total_amount');
            $budget = BudgetAllocation::where('budget_allocation_no', $no)->first();
            $department = Department::findOrFail($budget->department_id);
            $departmentBudget = $budget->total_amount ?? 0;

            $finalAmount = $totalAmount - $departmentBudget;
            if($finalAmount > 0){
                $department->deposit(abs($finalAmount));
            }
            else if($finalAmount < 0)
            {
                $department->withdraw(abs($finalAmount));
            }
            $departmentBudget += $finalAmount;
            $budget->update([
                'total_amount' => $departmentBudget
            ]);
            // Commit transaksi
            DB::commit();
            return True;

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return $e;
        }
    }
}
