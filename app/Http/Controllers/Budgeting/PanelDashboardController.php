<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\CategoryMaster;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PanelDashboardController extends Controller
{
    public function index()
    {
        return view(".page.budgeting.dashboard.chart.index");
    }

    public function getPieChart(Request $request)
    {
        $query = Wallet::query()
        ->when($request->query('year'), function($query, $year){
            return $query->whereYear('created_at', $year);
        });

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
        $data = CategoryMaster::query()
            ->withSum(['category' => function ($query) use ($request) {
                $query->when($request->query('year'), function ($query, $year) {
                    return $query->whereYear('created_at', $year);
                })
                    ->when($request->query('department_id'), function ($query, $department) {
                        return $query->where('department_id', $department);
                    });
            }], 'grand_total')
            ->get();

        return response()->json($data);
    }

    public function getrequest(Request $request)
    {
        $requests = BudgetRequest::with(['fromDepartment', 'toDepartment'])
        ->when($request->query('year'), function($query, $year){
            return $query->whereYear('created_at', $year);
        })
        ->when($request->query('department_id'), function($query, $department){
            return $query->where('from_department_id', $department);
        })
        ->latest()->take(10)->get();
        return response()->json($requests);
    }

    public function getpurchase(Request $request)
    {
        $purchases = Purchase::with(['category', 'department'])
        ->when($request->query('year'), function($query, $year){
            return $query->whereYear('created_at', $year);
        })
        ->when($request->query('department_id'), function($query,$department){
            return $query->where('department_id', $department);
        })
        ->latest()->take(10)->get();
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

        return response()->json($departments);
    }

    public function purchaseTracking(Request $request)
    {
        $year = $request->query('year', date('Y'));

        $query = Purchase::query()
            ->join('departments', 'purchases.department_id', '=', 'departments.id')
            ->select(
                DB::raw('count(purchases.id) as count'),
                DB::raw('MONTH(purchases.created_at) as month_number'),
                'departments.department_name as department_name'
            )
            ->whereYear('purchases.created_at', $year)
            ->when($request->query('department_id'), function ($query, $department) {
                return $query->where('purchases.department_id', $department);
            })
            ->groupBy('department_name', 'month_number')
            ->orderBy('month_number', 'ASC')
            ->get();

        $labels = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        $datasets = [];
        $departmentData = $query->groupBy('department_name');

        $colors = [
            ['border' => 'rgb(255, 99, 132)',  'bg' => 'rgba(255, 99, 132, 0.2)'],
            ['border' => 'rgb(54, 162, 235)',  'bg' => 'rgba(54, 162, 235, 0.2)'],
            ['border' => 'rgb(255, 205, 86)',  'bg' => 'rgba(255, 205, 86, 0.2)'],
            ['border' => 'rgb(75, 192, 192)',   'bg' => 'rgba(75, 192, 192, 0.2)'],
            ['border' => 'rgb(153, 102, 255)', 'bg' => 'rgba(153, 102, 255, 0.2)'],
            ['border' => 'rgb(255, 159, 64)',  'bg' => 'rgba(255, 159, 64, 0.2)'],
            ['border' => 'rgb(46, 204, 113)',   'bg' => 'rgba(46, 204, 113, 0.2)'],
            ['border' => 'rgb(231, 76, 60)',    'bg' => 'rgba(231, 76, 60, 0.2)'],
            ['border' => 'rgb(149, 165, 166)', 'bg' => 'rgba(149, 165, 166, 0.2)'],
            ['border' => 'rgb(241, 196, 15)',   'bg' => 'rgba(241, 196, 15, 0.2)'],
            ['border' => 'rgb(26, 188, 156)',   'bg' => 'rgba(26, 188, 156, 0.2)'],
            ['border' => 'rgb(52, 73, 94)',      'bg' => 'rgba(52, 73, 94, 0.2)']
        ];
        $colorIndex = 0;
        $hoverOffset = 4 ;

        foreach ($departmentData as $departmentName => $data) {
            $monthlyCounts = array_fill(0, 12, 0);
            foreach ($data as $item) {
                $monthlyCounts[$item->month_number - 1] = $item->count;
            }

            $color = $colors[$colorIndex % count($colors)];

            $datasets[] = [
                'label' => $departmentName,
                'data' => $monthlyCounts,
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'hoverOffset' => $hoverOffset,
                'fill' => true,
                'tension' => 0.1
            ];
            $colorIndex++;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }

    public function balanceTracking(Request $request){
        $year = $request->query('year');
        $departmentId = $request->query('department_id');
        $purchase = Purchase::query();

        $totalBalance = Wallet::query()
            ->when($year, function ($query, $year) {
                return $query->where('name', $year);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('holder_id', $departmentId);
            })
            ->sum('balance');

        $totalExpense = $purchase
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('department_id', $departmentId);
            })
            ->sum('grand_total');

        $totalPurchase = Purchase::query()
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('department_id', $departmentId);
            })
            ->count();

        return response()->json([
            'total_balance' => $totalBalance ?? 0,
            'total_expense' => $totalExpense ?? 0,
            'total_purchase'   => $totalPurchase
        ]);
    }
}
