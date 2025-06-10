<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetApproval;
use App\Models\Budgeting\BudgetApprover;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use Carbon\Carbon;
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
            $year = now()->format('Y');
            if(!$toDept->hasWallet($year))
            {
                DB::rollback();
                throw new \Exception("The selected department has insufficient budget.");
            }
            if($toDept->balanceForYear($year) < $validatedData['amount']){
                DB::rollback();
                throw new \Exception("The selected department has insufficient budget.");
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
            return response()->json(['message' => 'Budget-request successfully created!'], 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json([
                'message' => 'There was an error creating the budget-request: ' . $e->getMessage()
            ], 500); // status 500 = server error
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

            // check purchase
            $purchase = Purchase::where('purchase_no', $budget->budget_purchase_no)->first();
            if($purchase)
            {
                // Simulasi request jika budget approved
                $purchaseController = new PurchaseController();
                $result = null;
                if($validatedData['action'] === 'approve'|| $validatedData['action'] === 'approve with review')
                {
                    $request = new Request([
                        'budget_req_no' => $approval->budget_req_no,
                        'status' => 'approve',
                        'token' => $approval->token,
                        'nik' => $approval->nik
                    ]);
                    $result = $purchaseController->approved($request);
                }
                else
                {
                    $request = new Request([
                        'budget_req_no' => $approval->budget_req_no,
                        'nik' => $approval->nik,
                        'feedback' => $validatedData['reviewTextArea']
                    ]);
                    $result = $purchaseController->submitRejectFeedback($request);
                }

                if ($result instanceof \Illuminate\Http\RedirectResponse) {
                    if (session()->has('error')) {
                        throw new \Exception(session('error'));
                    }
                }

                // Commit transaksi
                DB::commit();
                return response()->json(['message' => 'Budget-request successfully ' . $validatedData['action'] .'!'], 200);  
            } 
            else
            {

                if($approval->status != 'pending')
                {
                    DB::rollback();
                    throw new \Exception("Approval status is not pending.");
                }

                $fromDept = Department::findOrFail($budget->from_department_id);
                $toDept = Department::findOrFail($budget->to_department_id);

                // Get year from budget no
                $parts = explode('/', $budget->budget_req_no);
                $yearSuffix = $parts[3];
                $year = '20' . $yearSuffix;

                if(!$toDept->hasWallet($year))
                {
                    DB::rollback();
                    throw new \Exception("Your department has insufficient budget this year.");
                }
                if($toDept->balanceForYear($year) < $validatedData['amount']){
                    DB::rollback();
                    throw new \Exception("Your department has insufficient budget this year.");
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
                    $toDept->getWallet($year)->transfer($fromDept->getWallet($year), $budget->amount);
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
                    ->event(ucfirst($validatedData['action']))
                    ->causedBy($user)
                    ->withProperties(['no' => $budget->budget_req_no, 'action' => $validatedData['action'],
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
                return response()->json(['message' => 'Budget-request successfully ' . $validatedData['action'] .'!'], 200);
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json([
                'message' => 'There was an error updating the budget-request: ' . $e->getMessage()
            ], 500); // status 500 = server error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Budget-request cannot be deleted!']);
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $user = Auth::user();

            $budget = BudgetRequest::where('budget_req_no', str_replace('-', '/', $id))->firstOrFail();
            if($budget->status == 'pending')
            {
                $budget->delete();
            }
            else
            {
                throw new \Exception("Budget-Request status is not pending.");
            }
            
            activity()
                ->performedOn($budget)
                ->inLog('budget-request')
                ->event('Delete')
                ->causedBy($user)
                ->withProperties(['no' => $budget->budget_req_no, 'action' => 'delete',
                'data' => [
                    'budget_req_no' => $budget->budget_req_no,
                    'from_department' => $budget->from_department,
                    'budget_purchase_no' => $budget->budget_purchase_no,
                    'to_department' => $budget->to_department,
                    'amount' => $budget->amount,
                    'reason' => $budget->reason,
                    'status' => $budget->status,
                    'feedback' => $budget->feedback
                ]])
                ->log('Delete budget-request ' . $budget->budget_req_no . ' by ' . $user->name . ' at ' . now());
                
            // Commit transaksi
            DB::commit();
            return response()->json(['message' => 'Budget-request successfully deleted!'], 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json([
                'message' => 'There was an error deleting the budget-request: ' . $e->getMessage()
            ], 500); // status 500 = server error
        }
    }

    public function getBudgetRequestList(Request $request){
        $year = $request->has('year') && $request->year != '' 
            ? $request->year 
            : Carbon::now()->year;
            
        $yearSuffix = substr($year, -2); // '2026' -> '26'

        $user = Auth::user();
        $query = BudgetRequest::with('fromDepartment', 'toDepartment');
        /** @var User $user */
        if(!$user->hasRole(['super-admin', 'admin']))
        {
            $query->where('from_department_id', $user->department->id);
        }
        $budgets = $query->where(DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(budget_req_no, '/', 4), '/', -1)"), '=', $yearSuffix)
                        ->get();

        return response()->json($budgets);
    }

    public function getBudgetRequestNo(Request $request)
    {
        $departmentId = $request->input('departmentId');
        
        // Ambil departemen berdasarkan ID
        $department = Department::findOrFail($departmentId);
        $departmentCode = str_replace(" ","", strtoupper(substr($department->department_name, 0, 3))); // Ambil 3 huruf pertama nama departemen
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


    public function getBudgetRequestYear()
    {
        $years = BudgetRequest::select(DB::raw("CONCAT('20', SUBSTRING_INDEX(SUBSTRING_INDEX(budget_req_no, '/', 4), '/', -1)) as year"))
                    ->groupBy('year')
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();

        return response()->json($years);
    }
}
