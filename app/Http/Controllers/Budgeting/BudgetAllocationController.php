<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;


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

            // bikin wallet
            $year = now()->addYear()->format('Y');
            $dept = Department::findOrFail($validatedData['department']);
            if(!$dept->hasWallet($year))
            {
                $dept->createWallet([
                    'name' => $year,
                    'slug' => Str::slug($year),
                ]);
            }
            
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
            return response()->json(['message' => 'Budget-allocation successfully created!'], 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json([
                'message' => 'There was an error creating the budget-allocation: ' . $e->getMessage()
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
                'description' => 'nullable|string|max:255',
            ]);

            $user = Auth::user();

            $budget = BudgetAllocation::where('budget_allocation_no', str_replace('-', '/', $id))->firstOrFail();
            $budgetOld = clone $budget;
            $budget->update([
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
            return response()->json(['message' => 'Budget-allocation successfully updated!'], 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json([
                'message' => 'There was an error updating the budget-allocation: ' . $e->getMessage()
            ], 500); // status 500 = server error
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

            $budget = BudgetAllocation::where('budget_allocation_no', str_replace('-', '/', $id))->firstOrFail();
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
            return response()->json(['message' => 'Budget-allocation successfully deleted!'], 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();
            return response()->json([
                'message' => 'There was an error deleting the budget-allocation: ' . $e->getMessage()
            ], 500); // status 500 = server error
        }
    }

    public function getBudgetData(Request $request){
        $year = $request->has('year') && $request->year != '' 
            ? $request->year 
            : Carbon::now()->year;
        $budgets = BudgetAllocation::with('department.wallet')->whereYear('created_at', $year)->get();
        return response()->json($budgets);
    }
    
    public function getBudgetNo(Request $request)
    {
        $departmentId = $request->input('departmentId');
        // Ambil departemen berdasarkan ID
        $department = Department::findOrFail($departmentId);
        $departmentCode = str_replace(" ","", strtoupper(substr($department->department_name, 0, 3))); // Ambil 3 huruf pertama nama departemen
        $year = now()->addYear()->format('y');
        // Cari alokasi terakhir yang dimulai dengan CAPEX/{kodeDepartemen}/{tahun}
        $lastAllocation = BudgetAllocation::where('budget_allocation_no', 'like', 'CAPEX/'.$departmentCode.'/'.$year.'/%')
                                        ->latest()
                                        ->first();

        // Ambil angka urutan terakhir dari nomor alokasi
        $lastNumber = $lastAllocation ? (int) substr($lastAllocation->budget_allocation_no, -4) : 0;

        // Menambahkan 1 dan memastikan nomor urut 4 digit
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Menghasilkan nomor alokasi baru
        return "CAPEX/{$departmentCode}/{$year}/{$newNumber}";
    }

    public function getBudgetAllocationYear()
    {
        $years = BudgetAllocation::select(DB::raw('YEAR(created_at) as year'))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

        return response()->json($years);
    }
}
