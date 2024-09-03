<?php

namespace App\Http\Controllers;

use App\Models\QAD\Item;
use App\Models\QAD\RequisitionMaster;
use App\Models\QAD\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total item, supplier, user, dan requisition master
        $totalItem = Item::count();
        $totalSupplier = Supplier::count();
        $totalUser = User::count();
        $totalRequisitionMaster = RequisitionMaster::count();

        // Ambil tahun-tahun unik dari data RequisitionMaster
        $availableYears = RequisitionMaster::selectRaw('YEAR(rqmReqDate) AS year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Mendapatkan tahun saat ini
        $currentYear = date('Y');
        $selectedYear = $currentYear;

        // Inisialisasi data jumlah PR berdasarkan bulan dan tahun
        $jumlahDibuat = [];
        $jumlahApproved = [];
        $jumlahUnapproved = [];

        // Query untuk menghitung jumlah PR berdasarkan bulan dan tahun
        $requisitions = RequisitionMaster::selectRaw('MONTH(rqmReqDate) as month, YEAR(rqmReqDate) as year, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->get();

        // Mengisi data jumlah PR berdasarkan bulan dan tahun dari hasil query
        foreach ($requisitions as $requisition) {
            $month = $requisition->month;
            $year = $requisition->year;
            $total = $requisition->total;

            if (!isset($jumlahDibuat[$month])) {
                $jumlahDibuat[$month] = 0;
            }
            if (!isset($jumlahApproved[$month])) {
                $jumlahApproved[$month] = 0;
            }
            if (!isset($jumlahUnapproved[$month])) {
                $jumlahUnapproved[$month] = 0;
            }

            $jumlahDibuat[$month] += $total;

            // Misalnya, Anda menganggap 'Approved' dan 'Unapproved' sebagai status yang terhitung
            if ($requisition->rqmAprvStat == 'Approved') {
                $jumlahApproved[$month] += $total;
            } elseif ($requisition->rqmAprvStat == 'Unapproved') {
                $jumlahUnapproved[$month] += $total;
            }
        }

        return view('dashboard', compact('currentYear', 'totalItem', 'totalSupplier', 'totalUser', 'totalRequisitionMaster', 'jumlahDibuat', 'jumlahApproved', 'jumlahUnapproved', 'availableYears', 'selectedYear'));
    }

    public function getRequisitionsByYear($year)
    {
        // Ambil data RequisitionMaster berdasarkan tahun yang dipilih
        $requisitions = RequisitionMaster::whereYear('rqmReqDate', $year)->get();

        // Inisialisasi array untuk menyimpan jumlah PR yang terbuat, Approved, dan Unapproved
        $jumlahDibuat = array_fill(1, 12, 0); // 12 bulan, mulai dari bulan 1
        $jumlahApproved = array_fill(1, 12, 0);
        $jumlahUnapproved = array_fill(1, 12, 0);

        // Loop untuk menghitung jumlah PR berdasarkan bulan pada tahun yang dipilih
        foreach ($requisitions as $requisition) {
            $reqMonth = date('n', strtotime($requisition->rqmReqDate));

            $jumlahDibuat[$reqMonth]++;

            if ($requisition->rqmAprvStat == 'Approved') {
                $jumlahApproved[$reqMonth]++;
            } elseif ($requisition->rqmAprvStat == 'Unapproved') {
                $jumlahUnapproved[$reqMonth]++;
            }
        }

        // Format data untuk respons JSON
        $data = [
            'x' => ['x', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Label bulan
            'jumlahDibuat' => array_values($jumlahDibuat),
            'jumlahApproved' => array_values($jumlahApproved),
            'jumlahUnapproved' => array_values($jumlahUnapproved),
        ];

        return response()->json($data);
    }
}
