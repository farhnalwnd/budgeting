<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetList;
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
            $budget = BudgetList::create([
                'budget_allocation_no' => $validatedData['no'],
                'name' => $validatedData['name'],
                'category_id' => $validatedData['category'],
                'quantity' => $validatedData['quantity'],
                'um' => $validatedData['um'],
                'default_amount' => $validatedData['amount'],
                'total_amount' => $validatedData['total']
            ]);
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-list')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'create'])
                ->log('Create budget-list ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-list successfully created!', 'success');
            return redirect()->route('budget-list.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error creating the budget-list.'.$e->getMessage(), 'error');
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
            $budget->update([
                'budget_allocation_no' => $validatedData['no'],
                'name' => $validatedData['name'],
                'category_id' => $validatedData['category'],
                'quantity' => $validatedData['quantity'],
                'um' => $validatedData['um'],
                'default_amount' => $validatedData['amount'],
                'total_amount' => $validatedData['total']
            ]);
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-list')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'update'])
                ->log('Update budget-list ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-list successfully updated!', 'success');
            return redirect()->route('budget-list.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error updating the budget-list.'.$e->getMessage(), 'error');
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
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-list')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_allocation_no, 'action' => 'delete'])
                ->log('Delete budget-list ' . $budget->budget_allocation_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-list successfully deleted!', 'success');
            return redirect()->route('budget-list.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error deleting the budget-list.'.$e->getMessage(), 'error');
            return back();
        }
    }

    public function getBudgetList(){
        $budgets = BudgetList::with('category')->get();
        return response()->json($budgets);
    }
}
