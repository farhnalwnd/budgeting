<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetApproval;
use App\Models\Budgeting\BudgetApprover;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('page.budgeting.management.budget-request.index');
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
                'from_department' => 'required|exists:departments,department_name',
                'to_department' => 'required|exists:departments,id',
                'amount' => 'required|numeric',
                'reason' => 'required|string|max:255'
            ]);

            $toDept = Department::findorfail($validatedData['to_department']);

            if($toDept->balance < $validatedData['amount']){
                DB::rollback();
                Alert::toast("The selected department's budget is insufficient.", 'error');
                return back();
            }


            $user = Auth::user();
            
            $budget = BudgetRequest::create([
                'budget_req_no' => $validatedData['no'],
                'from_department_id' => $user->department->id,
                'to_department_id' => $validatedData['to_department'],
                'amount' => $validatedData['amount'],
                'reason' => $validatedData['reason']
            ]);

            $approverNik = BudgetApprover::where('department_id',$validatedData['to_department'])->first();
            BudgetApproval::create([
                'budget_req_no' => $validatedData['no'],
                'nik' => $approverNik->nik,
                'status' => 'pending',
                'token' => Str::uuid()
            ]);

            activity()
                ->performedOn($budget)
                ->inLog('budget-request')
                ->event('Create')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_req_no, 'action' => 'create',
                'data' => [
                    'budget_req_no' => $budget->budget_req_no,
                    'from_department' => $user->department->department_name,
                    'budget_purchase_no' => $budget->budget_purchase_no,
                    'to_department' => $toDept->department_name,
                    'amount' => $budget->amount,
                    'reason' => $budget->reason
                ]])
                ->log('Create budget-request ' . $budget->budget_req_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-request successfully created!', 'success');
            return redirect()->route('budget-request.index');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error creating the budget-request.'.$e->getMessage(), 'error');
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
                'from_department' => 'required|exists:departments,department_name',
                'to_department' => 'required|exists:departments,department_name',
                'amount' => 'required|numeric',
                'reason' => 'required|string|max:255',
                'action' => 'required|string|in:approve,approve with review,reject',
                'reviewTextArea' => 'nullable|string'
            ]);

            $budget = BudgetRequest::findOrFail($id);
            $approval = BudgetApproval::where('budget_req_no', $budget->budget_req_no)->first();

            $fromDept = Department::findOrFail($budget->from_department_id);
            $toDept = Department::findOrFail($budget->to_department_id);

            if($toDept->balance < $validatedData['amount']){
                DB::rollback();
                Alert::toast("The selected department's budget is insufficient.", 'error');
                return back();
            }


            $user = Auth::user();
            $status = null;
            $review = null;
            if($validatedData['action'] === 'approve' || $validatedData['action'] === 'approve with review')
            {
                $status = 'Approved';
                if($validatedData['action'] === 'approve with review')
                {
                    $review = $validatedData['reviewTextArea'];
                }
                $toDept->transfer($fromDept, $budget->amount);
            }
            else
            {   
                $status = 'Rejected';
                $review = $validatedData['reviewTextArea'];
            }

            $budget->update([
                'status' => $status,
                'feedback' => $review
            ]);

            $approval->update([
                'status' => $status,
                'feedback' => $review,
                'token' => null
            ]);

            activity()
                ->performedOn($budget)
                ->inLog('budget-request')
                ->event('Approve')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_req_no, 'action' => 'approve',
                'data' => [
                    'budget_req_no' => $budget->budget_req_no,
                    'from_department' => $user->department->department_name,
                    'budget_purchase_no' => $budget->budget_purchase_no,
                    'to_department' => $toDept->department_name,
                    'amount' => $budget->amount,
                    'reason' => $budget->reason,
                    'status' => $budget->status,
                    'feedback' => $budget->feedback
                ]])
                ->log(ucfirst($validatedData['action']) . ' budget-request ' . $budget->budget_req_no . ' by ' . $user->name . ' at ' . now());

            // Commit transaksi
            DB::commit();
            Alert::toast('Budget-request successfully ' . $validatedData['action'] .'!' , 'success');
            return redirect()->route('budget-request.approval');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            Alert::toast('There was an error approving the budget-request.'.$e->getMessage(), 'error');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getBudgetRequestList(){
        $budgets = BudgetRequest::with('fromDepartment', 'toDepartment')->get();
        return response()->json($budgets);
    }

    public function getBudgetRequestNo(Request $request)
    {
        $departmentId = $request->input('departmentId');
        
        // Ambil departemen berdasarkan ID
        $department = Department::findOrFail($departmentId);
        $departmentCode = str_replace(" ","", strtoupper(substr($department->department_name, 0, 3))); // Ambil 3 huruf pertama nama departemen

        // Cari alokasi terakhir yang dimulai dengan CAPEX/{kodeDepartemen}
        $lastAllocation = BudgetRequest::where('budget_req_no', 'like', 'REQCAPEX/'.$departmentCode.'/%')
                                        ->latest()
                                        ->first();
                                        
        // Ambil angka urutan terakhir dari nomor alokasi
        $lastNumber = $lastAllocation ? (int) substr($lastAllocation->budget_req_no, -4) : 0;

        // Menambahkan 1 dan memastikan nomor urut 4 digit
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Menghasilkan nomor alokasi baru
        return "REQCAPEX/{$departmentCode}/{$newNumber}";
    }
    
    

    public function BudgetRequestApprovalIndex(){
        confirmDelete();
        return view('page.budgeting.management.budget-request-approval.index');
    }

    public function getBudgetRequestApprovalList(){
        $user = Auth::user();
        $approvals = BudgetApprover::where('nik', $user->nik)->get();
        if($approvals->isNotEmpty())
        {
            $departmentIds = $approvals->pluck('department_id'); 
            $budgets = BudgetRequest::with('fromDepartment', 'toDepartment')
                ->whereIn('to_department_id', $departmentIds)
                ->where('status', 'pending')
                ->get();
                return response()->json($budgets);
        }
        else
        {
            return response()->json(null);
        }

    }
}
