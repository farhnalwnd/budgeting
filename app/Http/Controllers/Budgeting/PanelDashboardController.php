<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PanelDashboardController extends Controller
{
    public function index()
    {
        return view(".page.budgeting.dashboard.chart.index");
    }

    public function getPieChart(Request $request)
    {
        $query = Wallet::query();

        if ($request->has('department_id') && !empty($request->department_id)) {
            $query->where('department_id', $request->department_id);
        }


        $data = $query->select('holder_id')
            ->selectRaw('SUM(balance) as total_balance')
            ->with('department:id,department_name')
            ->groupBy('holder_id')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->department->department_name,
                    'value' => $item->total_balance
                ];
            });

        return response()->json($data);
    }

    public function getBarChart(Request $request)
    {
        $data = DB::table('purchases')
            ->join('category_masters', 'purchases.category_id', '=', 'category_masters.id')
            ->select('category_masters.name as category_name', DB::raw('SUM(purchases.grand_total) as total_spending'))
            ->groupBy('category_masters.name')
            ->get();

        return response()->json($data);
    }

    public function getrequest(Request $request)
    {
        $year = $request->query('year');
        $department = $request->query('department_id');
        $query = BudgetRequest::with(['fromDepartment', 'toDepartment']);
        if($year){
            $query->whereYear('created_at', $year);
        }
        if($department){
            $query->where('from_department_id', $department);
        }
        $requests = $query->latest()->take(10)->get();
        return response()->json($requests);
    }

    public function getpurchase(Request $request)
    {
        $year = $request->query('year');
        $department = $request->query('department_id');
        $query = Purchase::with(['category', 'department']);
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        if($department){
            $query->where('department_id', $department);
        }
        $purchases = $query->latest()->take(10)->get();
        return response()->json($purchases);
    }

    public function getchartYear()
    {
        $years = Wallet::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json($years);
    }

    public function getchartDepartment()
    {
        $holderIds = Wallet::pluck('holder_id');

        $departments = Department::whereIn('id', $holderIds)
            ->orderBy('department_name')
            ->get(['id', 'department_name']);

        Log::info($departments);
        return response()->json($departments);
    }
}
