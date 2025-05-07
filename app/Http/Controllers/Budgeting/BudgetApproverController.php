<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetApprover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetApproverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('roleuser.approver.index');
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
                'department' => 'required|exists:departments,id',
                'nik' => 'required|exists:users,nik'
            ]);

            $user = Auth::user();
            $approver = BudgetApprover::create([
                'department_id' => $validatedData['department'],
                'nik' => $validatedData['nik']
            ]);
            
            activity()
                ->performedOn($approver)
                ->inLog('budget-approver')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $approver->id, 'action' => 'create',
                'data' => [
                    'department_id' => $approver->department_id,
                    'nik' => $approver->nik
                ]])
                ->log('Create budget-approver ' . $approver->id . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-approver successfully created!', 'success');
            return redirect()->route('approver.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error creating the budget-approver.'.$e->getMessage(), 'error');
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
                'department' => 'required|exists:departments,id',
                'nik' => 'required|exists:users,nik'
            ]);

            $user = Auth::user();
            $approver = BudgetApprover::find($id);
            $approverOld = clone $approver;
            $approver->update([
                'department_id' => $validatedData['department'],
                'nik' => $validatedData['nik']
            ]);
            
            activity()
                ->performedOn($approver)
                ->inLog('budget-approver')
                ->event('Update')
                ->causedBy($user)
                ->withProperties(['no' => $approver->id, 'action' => 'update',
                'oldData' => [
                    'department_id' => $approverOld->department_id,
                    'nik' => $approverOld->nik
                ],
                'newData' => [
                    'department_id' => $approver->department_id,
                    'nik' => $approver->nik
                ]])
                ->log('Update budget-approver ' . $approver->id . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-approver successfully updated!', 'success');
            return redirect()->route('approver.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error updating the budget-approver.'.$e->getMessage(), 'error');
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
            $approver = BudgetApprover::findOrFail($id);
            $approver->delete();
            
            activity()
                ->performedOn($approver)
                ->inLog('budget-approver')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $approver->id, 'action' => 'delete',
                'data' => [
                    'department_id' => $approver->department_id,
                    'nik' => $approver->nik
                ]])
                ->log('Delete budget-approver ' . $approver->id . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-approver successfully deleted!', 'success');
            return redirect()->route('approver.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error deleting the budget-approver.'.$e->getMessage(), 'error');
            return back();
        }
    }

    public function getApproverData(){
        $budgets = BudgetApprover::with('department', 'user')->get();
        return response()->json($budgets);
    }
}
