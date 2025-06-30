<?php

namespace App\Http\Controllers\Budgeting;

use App\Http\Controllers\Controller;
use App\Models\Budgeting\BudgetAllocation;
use App\Models\Budgeting\BudgetRequest;
use App\Models\Budgeting\Purchase;
use App\Models\Department;
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
    public function index(Request $request)
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
        // New Report
        try{
            $user = Auth::user();
            $year = $request->has('year') && $request->year != '' 
            ? $request->year 
            : Carbon::now()->year;
            /** @var User $user */
            if($user->hasRole(['super-admin', 'admin'])) // Jika user adalah admin
            {
                // Mengambil purchase pada tahun yang dipilih
                $query = Purchase::with('department', 'category', 'detail', 'budgetRequest.toDepartment', 'budgetRequest.fromDepartment');
                // Cek apakah ada department yang dipilih
                if ($request->has('department_name') && $request->department_name != '') {
                    $query->whereHas('department', function ($query) use ($request) {
                        $query->where('department_name', $request->department_name);
                    });
                }
                $raw = $query->where('status', 'approved')
                        ->whereYear('created_at', $year)
                        ->get();
                
                // Jika empty return kosong
                if ($raw->isEmpty()) {
                    return response()->json();
                } 
            }
            else // Jika user bukan admin
            {
                $raw = Purchase::with('department')
                    ->where('department_id', $user->department_id)
                    ->where('status', 'approved') // Memastikan department_id sesuai dengan user
                    ->whereYear('created_at', $year)
                    ->get();

                // Jika empty return kosong
                if ($raw->isEmpty()) {
                    return response()->json();
                } 

            }    
                $grouped = $raw->groupBy(fn($item) => $item->department->department_name);
                $final = [];
                $grand_total = 0;
                $grand_total_actual = 0;
                // meng group data per department
                foreach ($grouped as $dept => $rows) {
                    $final[] = (object)[ // membuat Nama department
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'is_subcategory' => false,
                        'is_grandtotal' => false,
                        'purchase_no' => $dept,
                        'item_name' => '',
                        'total_amount' => '',
                        'PO' => '',
                        'actual_amount' => '',
                        'remarks' => ''
                    ];

                    // Group data sesuai kategori
                    $categoryGrouped = $rows->groupBy(fn($item) => $item->category->name ?? '-');
                    // Buat variable untuk menampung total purchase dengan harga actual
                    $deptSubtotalActual = 0;
                    foreach ($categoryGrouped as $cat => $catRows) {
                        // Tambahkan baris sub-subtotal kategori
                        $final[] = (object)[
                            'department_name' => $dept,
                            'is_subtotal' => false,
                            'is_subcategory' => true,
                            'is_grandtotal' => false,
                            'purchase_no' => $cat,
                            'item_name' => '',
                            'total_amount' => '',
                            'PO' => '',
                            'actual_amount' => '',
                            'remarks' => ''
                        ];

                        // Tambahkan purchase untuk setiap kategori
                        foreach ($catRows as $row) {
                            $row->department_name = $dept;
                            $row->is_subtotal = false;
                            $row->is_subcategory = false;
                            $row->is_grandtotal = false;
                            $row->item_name = collect($row->detail)->map(function ($item) {
                                                    return "{$item->item_name} ({$item->quantity} {$item->um})";
                                                })->filter()->implode(', ');
                            $row->remarks = collect($row->detail)->map(function ($item) {
                                                    return "{$item->remarks}";
                                                })->filter()->implode(', ');
                                                
                            $deptSubtotalActual += $row->actual_amount ?? $row->grand_total;
                            $row->total_amount = '-' . $row->grand_total;
                            $row->actual_amount = $row->actual_amount ? '-' . $row->actual_amount : '';
                            $final[] = $row;
                        }
                    }

                    // Tambahkan subtotal untuk setiap department
                    $final[] = (object)[ // Membuat Subtotal per department
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'is_subcategory' => false,
                        'is_grandtotal' => false,
                        'purchase_no' => 'Subtotal purchase for ' . $dept,
                        'item_name' => '',
                        'total_amount' => '-' . $rows->sum('grand_total'),
                        'PO' => '',
                        'actual_amount' => '-' . $deptSubtotalActual,
                        'remarks' => ''
                    ];

                    $department =  Department::where('department_name', $dept)->first();
                    // Ambil kode departemen dari nama departemen
                    $departmentCode = str_replace(" ","", strtoupper(substr($department->department_name, 0, 3))); // Ambil 3 huruf pertama nama departemen
                    $yearAllocation = substr((string)$year, -2); // Ambil 2 digit terakhir dari tahun
                    // Tampilkan setiap budget Request
                    $totalRequestAmount = 0;
                    
                    // Mengambil semua budget request yang berkaitan dengan department
                    $budgetRequests = BudgetRequest::with(['fromDepartment', 'toDepartment'])
                        ->where(function($q) use ($department) {
                            $q->where('from_department_id', $department->id)
                                ->orWhere('to_department_id', $department->id);
                        })
                        ->where('status', 'approved')
                        ->where(function($q) use ($yearAllocation) {
                            $q->where('budget_req_no', 'like', '%/' . $yearAllocation . '/%');
                        })
                        ->get();
                    
                    // Jika ada budget request, tampilkan
                    if ($budgetRequests) {
                        foreach ($budgetRequests as $budgetRequest) {
                            // Menentukan budget request from/to department
                            if ($budgetRequest->toDepartment && $budgetRequest->toDepartment->id == $department->id) {
                                $requestBudget = 'Request Budget from ' . $budgetRequest->fromDepartment->department_name;
                                $requestAmount = '-' . $budgetRequest->amount;
                                $totalRequestAmount -= $budgetRequest->amount;
                            } else if($budgetRequest->fromDepartment && $budgetRequest->fromDepartment->id == $department->id) {
                                $requestBudget = 'Request Budget to ' . $budgetRequest->toDepartment->department_name;
                                $requestAmount = $budgetRequest->amount;
                                $totalRequestAmount += $budgetRequest->amount;
                            }else{
                                // Jika budget request tidak diketahui from/to department
                                $requestBudget = 'Request Budget from/to unknown department';
                            }
                            $final[] = (object)[ // Menampilkan setiap budget request department
                                'department_name' => $dept,
                                'is_subtotal' => false,
                                'is_subcategory' => false,
                                'is_grandtotal' => false,
                                'purchase_no' => $requestBudget,
                                'item_name' => '',
                                'total_amount' => $requestAmount,
                                'PO' => '',
                                'actual_amount' => '',
                                'remarks' => $budgetRequest->reason
                            ];
                        }
                    }

                    $final[] = (object)[ // Menampilkan Total Budget Request per department
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'is_subcategory' => false,
                        'is_grandtotal' => false,
                        'purchase_no' => 'Subtotal budget request for ' . $dept,
                        'item_name' => '',
                        'total_amount' => $totalRequestAmount,
                        'PO' => '',
                        'actual_amount' => '',
                        'remarks' => ''
                    ];

                    // Cari alokasi budget, dimulai dengan CAPEX/{kodeDepartemen}/{tahun}
                    $departmentBudgetAllocation = BudgetAllocation::where('budget_allocation_no', 'like', 'CAPEX/'.$departmentCode.'/'.$yearAllocation.'/0001')
                                                    ->first();
                    // Jika tidak ada alokasi, set budget awal ke 0
                    if ($departmentBudgetAllocation) {
                        $initialBudget = $departmentBudgetAllocation->total_amount;
                    } else {    
                        $initialBudget = 0;
                    }
                    // Menghitung sisa budget
                    $remainingBudget = ($initialBudget + $totalRequestAmount)-$rows->sum('grand_total'); // Pengurangan budget awal dengan purchase dan budget request
                    $remainingActualBudget = $department->balanceForYear($year); // Mengambil sisa budget setiap department dari wallet
                    
                    $final[] = (object)[ // Menampilkan Budget Awal per department
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'is_subcategory' => false,
                        'is_grandtotal' => false,
                        'purchase_no' => 'Initial Budget for ' . $dept,
                        'item_name' => '',
                        'total_amount' => $initialBudget,
                        'PO' => '',
                        'actual_amount' => '',
                        'remarks' => ''
                    ];
                    $final[] = (object)[ // Menampilkan Sisa Budget per department
                        'department_name' => $dept,
                        'is_subtotal' => true,
                        'is_subcategory' => false,
                        'is_grandtotal' => false,
                        'purchase_no' => 'Remaining Budget for ' . $dept,
                        'item_name' => '',
                        'total_amount' => $remainingBudget, // Sisa budget dari pengurangan
                        'PO' => '',
                        'actual_amount' => $remainingActualBudget, // Sisa budget dari wallet
                        'remarks' => ''
                    ];

                    // Menghitung grand total
                    $grand_total += $remainingBudget; 
                    $grand_total_actual += $remainingActualBudget;
                    
                }

                
                // Jika pilih spesifik
                // if ($request->has('department_name') || $request->department_name !== '') {
                $final[] = (object)[ // Membuat grand total setiap department
                    'department_name' => 'ALL',
                    'is_subtotal' => false,
                    'is_subcategory' => false,
                    'is_grandtotal' => true,   
                    'purchase_no' => 'GRAND TOTAL',
                    'item_name' => '',
                    'total_amount' => $grand_total,
                    'PO' => '',
                    'actual_amount' => $grand_total_actual,
                    'remarks' => ''
                ];
                // }

                // return DataTables::of($final)->make(true);  

                return response()->json($final);
            
                // $grouped = $raw->groupBy(fn($item) => $item->department->department_name);
                // $final = [];
                
                // foreach ($grouped as $dept => $rows) {
                //     $final[] = (object)[
                //         'department_name' => $dept,
                //         'is_subtotal' => true,
                //         'purchase_no' => $dept,
                //         'item_name' => '',
                //         'total_amount' => '',
                //         'PO' => '',
                //         'actual_amount' => '',
                //         'remarks' => ''
                //     ];

                //     $categoryGrouped = $rows->groupBy(fn($item) => $item->category->name);

                //     foreach ($categoryGrouped as $cat => $catRows) {
                //         // Tambahkan baris sub-subtotal kategori
                //         $final[] = (object)[
                //             'department_name' => $dept,
                //             'is_subtotal' => false,
                //             'is_subcategory' => true,
                //             'purchase_no' => $cat,
                //             'item_name' => '',
                //             'total_amount' => '',
                //             'PO' => '',
                //             'actual_amount' => '',
                //             'remarks' => ''
                //         ];

                //         foreach ($catRows as $row) {
                //             $row->department_name = $dept;
                //             $row->is_subtotal = false;
                //             $row->item_name = collect($row->detail)->map(function ($item) {
                //                                         return "{$item->item_name} ({$item->quantity} {$item->um})";
                //                                     })->filter()->implode(', ');
                //             $row->remarks = collect($row->detail)->map(function ($item) {
                //                                     return "{$item->remarks}";
                //                                 })->filter()->implode(', ');
                //             $row->total_amount = $row->grand_total;
                //             $final[] = $row;
                //         }
                //     }

                    
                // }
                // $final[] = (object)[
                //     'department_name' => 'ALL',
                //     'is_subtotal' => true,
                //     'purchase_no' => 'GRAND TOTAL',
                //     'item_name' => '',
                //     'total_amount' => $raw->sum('grand_total'),
                //     'PO' => '',
                //     'actual_amount' => $raw->sum('actual_amount'),
                //     'remarks' => ''
                // ];

                // return response()->json($final);

        } catch (\Exception $e)
        { 
            return response()->json('Failed to get data. '.$e);
        }
    }
}
