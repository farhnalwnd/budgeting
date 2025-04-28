<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('page.budgeting.management.budget-allocation.index');
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
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $validatedData = $request->validate([
                'no' => 'required|string|max:255',
                'department' => 'required|exists:departments,id',
                'description' => 'nullable|string|max:255',
            ]);

            $user = Auth::user();
            $budget = BudgetAllocation::create([
                'budget_allocation_no' => $validatedData['no'],
                'department_id' => $validatedData['department'],
                'description' => $validatedData['description'] ?? null,
                'allocated_by' => $user->nik
            ]);
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-allocation')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'create',
                'data' => [
                    'budget_allocation_no' => $budget->budget_allocation_no,
                    'department_id' => $budget->department_id,
                    'description' => $budget->description ?? null,
                    'allocated_by' => $budget->allocated_by
                ]])
                ->log('Create budget-allocation ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-allocation successfully created!', 'success');
            return redirect()->route('budget-allocation.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error creating the budget-allocation.'.$e->getMessage(), 'error');
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
                'no' => 'required|string|max:255',
                'department' => 'required|exists:departments,id',
                'description' => 'nullable|string|max:255',
            ]);

            $user = Auth::user();

            $budget = BudgetAllocation::find($id);
            $budgetOld = clone $budget;
            $budget->update([
                'budget_allocation_no' => $validatedData['no'],
                'department_id' => $validatedData['department'],
                'description' => $validatedData['description'] ?? null
            ]);
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-allocation')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'update',
                'oldData' => [
                    'budget_allocation_no' => $budgetOld->budget_allocation_no,
                    'department_id' => $budgetOld->department_id,
                    'description' => $budgetOld->description ?? null,
                ],
                'newData' => [
                    'budget_allocation_no' => $budget->budget_allocation_no,
                    'department_id' => $budget->department_id,
                    'description' => $budget->description ?? null,
                ]])
                ->log('Update budget-allocation ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-allocation successfully updated!', 'success');
            return redirect()->route('budget-allocation.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error updating the budget-allocation.'.$e->getMessage(), 'error');
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

            $budget = BudgetAllocation::findOrFail($id);
            $budget->delete();
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-allocation')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'delete',
                'data' => [
                    'budget_allocation_no' => $budget->budget_allocation_no,
                    'department_id' => $budget->department_id,
                    'description' => $budget->description ?? null,
                    'allocated_by' => $budget->allocated_by
                ]])
                ->log('Delete budget-allocation ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-allocation successfully deleted!', 'success');
            return redirect()->route('budget-allocation.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error deleting the budget-allocation.'.$e->getMessage(), 'error');
            return back();
        }
    }

    public function getBudgetData(){
        $budgets = BudgetAllocation::with('department')->get();
        return response()->json($budgets);
    }

    public function getBudgetNo(){
        $year = date('y');
        $prefix = "{$year}CAPEX";
        $lastBudget = BudgetAllocation::where('budget_allocation_no', 'like', "{$prefix}%")->orderBy('budget_allocation_no', 'desc')->first();

        if ($lastBudget) {
            $lastNo = (int)substr($lastBudget->budget_allocation_no, -4);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }

        do {
            $newNoReg = $prefix . str_pad($newNo, 4, '0', STR_PAD_LEFT);
            $existingBudget = BudgetAllocation::where('budget_allocation_no', $newNoReg)->first();
            if ($existingBudget) {
                $newNo++;
            }
        } while ($existingBudget);

        return response()->json($newNoReg);
    }
}
