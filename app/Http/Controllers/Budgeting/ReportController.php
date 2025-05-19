<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\Purchase;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        confirmDelete();
        return view('page.budgeting.dashboard.report.index');
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
        //
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

    public function getReportYear()
    {
        $years = Purchase::select(DB::raw('YEAR(created_at) as year'))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

        return response()->json($years);
    }

    public function getReportData(Request $request)
    {
        try{
            $user = Auth::user();
            $year = $request->has('year') && $request->year != '' 
            ? $request->year 
            : Carbon::now()->year;
            if($user->hasRole(['super-admin', 'admin']))
            {
                $query = PurchaseDetail::with('master.department', 'master.category');
                // Cek apakah ada department yang dipilih
                if ($request->has('department_name') && $request->department_name != '') {
                    $query->whereHas('master.department', function ($query) use ($request) {
                        $query->where('department_name', $request->department_name);
                    });
                }
                $query->whereHas('master', function ($query) use ($request) {
                    $query->where('status', 'approved');
                });
                $query->whereYear('created_at', $year);
                $raw = $query->get();

                // Jika empty return kosong
                if ($raw->isEmpty()) {
                    return response()->json();
                } 
                
                $grouped = $raw->groupBy(fn($item) => $item->master->department->department_name);
                $final = [];

                // meng group data per department
                foreach ($grouped as $dept => $rows) {
                    $final[] = (object)[ // membuat Nama department
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'is_subcategory' => false,
                        'purchase_no' => $dept,
                        'item_name' => '',
                        'amount' => '',
                        'quantity' => '',
                        'total_amount' => '',
                        'remarks' => ''
                    ];

                    $categoryGrouped = $rows->groupBy(fn($item) => $item->master->category->name);

                    foreach ($categoryGrouped as $cat => $catRows) {
                        // Tambahkan baris sub-subtotal kategori
                        $final[] = (object)[
                            'department_name' => $dept,
                            'is_subtotal' => false,
                            'is_subcategory' => true,
                            'purchase_no' => $cat,
                            'item_name' => '',
                            'amount' => '',
                            'quantity' => '',
                            'total_amount' => '',
                            'remarks' => ''
                        ];

                        foreach ($catRows as $row) {
                            $row->department_name = $dept;
                            $row->is_subtotal = false;
                            $row->is_subcategory = false;
                            $final[] = $row;
                        }
                    }

                    // Jika tidak pilih department
                    if (!$request->has('department_name') || $request->department_name == '') {
                        $final[] = (object)[ // Membuat Subtotal per department
                            'department_name' => $dept,
                            'is_subtotal' => true,
                            'is_subcategory' => false,
                            'purchase_no' => 'Subtotal for ' . $dept,
                            'item_name' => '',
                            'amount' => 0,
                            'quantity' => $rows->sum('quantity'),
                            'total_amount' => $rows->sum('total_amount'),
                            'remarks' => ''
                        ];
                    }

                    
                }
                $final[] = (object)[ // Membuat grand total setiap department
                    'department_name' => 'ALL',
                    'is_subtotal' => true,
                    'is_subcategory' => false,
                    'purchase_no' => 'GRAND TOTAL',
                    'item_name' => '',
                    'amount' => 0,
                    'quantity' => $raw->sum('quantity'),
                    'total_amount' => $raw->sum('total_amount'),
                    'remarks' => ''
                ];

                // return DataTables::of($final)->make(true);  

                return response()->json($final);
            }
            else
            {
                $raw = PurchaseDetail::with('master.department')
                    ->whereHas('master', function ($query) use ($user) {
                        $query->where('department_id', $user->department_id)
                        ->where('status', 'approved'); // Pastikan department_id ada pada user
                    })
                    ->whereYear('created_at', $year)
                    ->get();

                // Jika empty return kosong
                if ($raw->isEmpty()) {
                    return response()->json();
                } 

                $grouped = $raw->groupBy(fn($item) => $item->master->department->department_name);
                $final = [];

                foreach ($grouped as $dept => $rows) {
                    $final[] = (object)[
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'purchase_no' => $dept,
                        'item_name' => '',
                        'amount' => '',
                        'quantity' => '',
                        'total_amount' => '',
                        'remarks' => ''
                    ];

                    foreach ($rows as $row) {
                        $row->department_name = $dept;
                        $row->is_subtotal = false;
                        $final[] = $row;
                    }
                }
                $final[] = (object)[
                    'department_name' => 'ALL',
                    'is_subtotal' => true,
                    'purchase_no' => 'GRAND TOTAL',
                    'item_name' => '',
                    'amount' => 0,
                    'quantity' => $raw->sum('quantity'),
                    'total_amount' => $raw->sum('total_amount'),
                    'remarks' => ''
                ];

                return response()->json($final);
            }

        } catch (\Exception $e)
        { 
            return response()->json('Failed to get data. '.$e);
        }
    }
}
