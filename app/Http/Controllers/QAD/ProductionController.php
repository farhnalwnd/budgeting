<?php

namespace App\Http\Controllers\QAD;

use Illuminate\Http\Request;
use App\Models\QAD\Production;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\QAD\StandardProduction;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\QAD\StandardWarehouseProduction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::all();
        return view('page.dataDashboard.production-index', compact('productions'));
    }

    private function httpHeader($req)
    {
        return array(
            'Content-type: text/xml;charset="utf-8"',
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'SOAPAction: ""',        // jika tidak pakai SOAPAction, isinya harus ada tanda petik 2 --> ""
            'Content-length: ' . strlen(preg_replace("/\s+/", " ", $req))
        );
    }

    public function getProductions()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $batchSize = 100; // Ukuran batch
        $offset = 0;
        $startDate = '2024-08-20'; // Tanggal mulai
        $endDate = date('Y-m-d'); // Tanggal akhir
        $totalNewItems = 0;
        $totalUpdatedItems = 0;

        do {
            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                    <Body>
                        <getproduction xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                            <ip_domain>'.$domain.'</ip_domain>
                            <ip_start_date>'.$startDate.'</ip_start_date>
                            <ip_end_date>'.$endDate.'</ip_end_date>
                            <ip_batch_size>'.$batchSize.'</ip_batch_size>
                            <ip_offset>'.$offset.'</ip_offset>
                        </getproduction>
                    </Body>
                </Envelope>';

            Log::channel('custom')->info('Mengirim request ke QAD', ['request' => $qdocRequest]);

            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout + 5,
                CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );

            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);
                curl_close($curl);
            } else {
                Log::channel('custom')->error('Gagal menghubungi server.');
                return redirect()->back()->with('error', 'Gagal menghubungi server.');
            }

            if (!$qdocResponse) {
                Log::channel('custom')->error('Tidak ada respons dari server.');
                return redirect()->back()->with('error', 'Tidak ada respons dari server.');
            }

            Log::channel('custom')->info('Menerima response dari QAD', ['response' => $qdocResponse]);

            $xmlResp = simplexml_load_string($qdocResponse);
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $qdocResult = (string) $xmlResp->xpath('//ns:getproductionResponse/ns:opOk')[0];

            $invoices = $xmlResp->xpath('//ns:getproductionResponse/ns:ttTrHistData/ns:ttTrHistDataRow');
            $jumlahItemBaru = 0;
            $jumlahItemUpdate = 0;

            if ($qdocResult == 'true') {
                foreach ($invoices as $item) {
                    $tr_trnbr = (string) $item->tr_trnbr;
                    $tr_nbr = (string) $item->tr_nbr;
                    $tr_effdate = (string) $item->tr_effdate;
                    $tr_type = (string) $item->tr_type;
                    $tr_prod_line = (string) $item->tr_prod_line;
                    $tr_part = (string) $item->tr_part;
                    $pt_desc1 = (string) $item->pt_desc1;
                    $tr_qty_loc = (string) $item->tr_qty_loc;
                    $Weight_in_KG = (string) $item->Weight_in_KG;
                    $Line = (string) $item->Line;
                    $pt_draw = (string) $item->pt_draw;

                    $existingInvoice = Production::where('tr_trnbr', $tr_trnbr)->first();

                    if ($existingInvoice) {
                        $existingInvoice->tr_nbr = $tr_nbr;
                        $existingInvoice->tr_effdate = $tr_effdate;
                        $existingInvoice->tr_type = $tr_type;
                        $existingInvoice->tr_prod_line = $tr_prod_line;
                        $existingInvoice->tr_part = $tr_part;
                        $existingInvoice->pt_desc1 = $pt_desc1;
                        $existingInvoice->tr_qty_loc = $tr_qty_loc;
                        $existingInvoice->Weight_in_KG = $Weight_in_KG;
                        $existingInvoice->Line = $Line;
                        $existingInvoice->pt_draw = $pt_draw;
                        $existingInvoice->save();
                        $jumlahItemUpdate++;
                    } else {
                        $newInvoice = new Production();
                        $newInvoice->tr_trnbr = $tr_trnbr;
                        $newInvoice->tr_effdate = $tr_effdate;
                        $newInvoice->tr_nbr = $tr_nbr;
                        $newInvoice->tr_type = $tr_type;
                        $newInvoice->tr_prod_line = $tr_prod_line;
                        $newInvoice->tr_part = $tr_part;
                        $newInvoice->pt_desc1 = $pt_desc1;
                        $newInvoice->tr_qty_loc = $tr_qty_loc;
                        $newInvoice->Weight_in_KG = $Weight_in_KG;
                        $newInvoice->Line = $Line;
                        $newInvoice->pt_draw = $pt_draw;
                        $newInvoice->save();
                        $jumlahItemBaru++;
                    }
                }
                $totalNewItems += $jumlahItemBaru;
                $totalUpdatedItems += $jumlahItemUpdate;
                Log::channel('custom')->info('Batch diproses', ['jumlahItemBaru' => $jumlahItemBaru, 'jumlahItemUpdate' => $jumlahItemUpdate]);
            } else {
                Log::channel('custom')->error('Gagal mengambil data dari server.');
                session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
                return redirect()->back();
            }

            $offset += $batchSize;
        } while (count($invoices) == $batchSize);

        session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $totalNewItems . ', Jumlah item update: ' . $totalUpdatedItems, 'toastType' => 'success']);
        return redirect()->back();
    }


    public function standardProduction()
    {
        $standardProductions = StandardProduction::all();
        return \view('page.standard.production-index',\compact('standardProductions'));
    }


    public function storeStandardProductions(Request $request)
    {
        $standardProduction = new StandardProduction();
        $standardProduction->line = $request->line;
        $standardProduction->total = $request->total;
        $standardProduction->save();
        Alert::toast('Standard Production successfully added', 'success');
        return redirect()->back();
    }

    public function updateStandardProductions(Request $request, $id)
    {
        $standardProduction = StandardProduction::findOrFail($id);
        $standardProduction->line = $request->line;
        $standardProduction->total = $request->total;
        $standardProduction->save();
        Alert::toast('Standard Production successfully updated', 'success');
        return redirect()->back();
    }

    public function destroyStandardProductions($id)
    {
        $standardProduction = StandardProduction::findOrFail($id);
        $standardProduction->delete();
        Alert::toast('Standard Production successfully deleted', 'success');
        return redirect()->back();
    }



    public function dashboardProduction(Request $request)
    {
        // Data untuk doughnut charts
        $doughnutData = [
            'A' => $this->getLineData('A'),
            'B' => $this->getLineData('B'),
            'C' => $this->getLineData('C'),
            'D' => $this->getLineData('D'),
            'E' => $this->getLineData('E')
        ];

        // Data untuk bar chart
        $barData = Production::whereDate('tr_effdate', Carbon::today())
            ->select('Line', DB::raw('SUM(tr_qty_loc) as total_qty'))
            ->groupBy('Line')
            ->get();

        // Data untuk standard production
        $standardData = StandardProduction::select('line as Line', DB::raw('SUM(total) as total'))
            ->groupBy('line')
            ->get();

        // Data untuk weight comparison
        $weightLastMonth = Production::whereMonth('created_at', now()->subMonth()->month)->sum('Weight_in_KG');
        $weightThisMonth = Production::whereMonth('created_at', now()->month)->sum('Weight_in_KG');
        $weightComparison = $this->getComparison($weightLastMonth, $weightThisMonth);

        // Data untuk quantity comparison
        $qtyLastMonth = Production::whereMonth('created_at', now()->subMonth()->month)->sum('tr_qty_loc');
        $qtyThisMonth = Production::whereMonth('created_at', now()->month)->sum('tr_qty_loc');
        $qtyComparison = $this->getComparison($qtyLastMonth, $qtyThisMonth);

        // Data untuk select options
        $months = Production::select(DB::raw('DISTINCT MONTH(created_at) as month'))->pluck('month');

        // Ambil data filter berdasarkan tanggal yang dipilih (misalnya dari query string)
        $filterData = $this->filterData($request); // Memanggil filterData

        // Mengambil data dari filterData
        $data = $filterData['data'];
        $grandTotal = $filterData['grandTotal'];

        // Mengolah shiftData
        $shifts = ['Shift 1', 'Shift 2', 'Shift 3'];
        $shiftData = [];

        foreach ($doughnutData as $line => $data) {
            foreach ($shifts as $shift) {
                $shiftData[$line][$shift] = $this->getShiftData($line, $shift);
            }
            $shiftData[$line]['total'] = $shiftData[$line]['Shift 1'] + $shiftData[$line]['Shift 2'] + $shiftData[$line]['Shift 3'];
        }

        // Grand total
        $grandTotalShift = array_sum(array_column($shiftData, 'total'));

        // Misalkan $data adalah hasil dari query yang Anda lakukan
        $data = [
            'data' => [
                // Data yang Anda tunjukkan sebelumnya
            ],
            'grandTotal' => 168174.7
        ];

        return view('dashboard.dashboardProduction', [
            'doughnutData' => $doughnutData,
            'barData' => $barData,
            'standardData' => $standardData,
            'weightLastMonth' => $weightLastMonth,
            'weightThisMonth' => $weightThisMonth,
            'weightComparison' => $weightComparison,
            'qtyLastMonth' => $qtyLastMonth,
            'qtyThisMonth' => $qtyThisMonth,
            'qtyComparison' => $qtyComparison,
            'months' => $months,
            'shiftData' => $shiftData,
            'data' => $data['data'], // Mengambil array dari 'data'
            'grandTotal' => $data['grandTotal'], // Mengambil grandTotal
            'grandTotalShift' => $grandTotalShift // Menambahkan grand total dari shift
        ]);
    }

    private function getLineData($line)
    {
        $actual = Production::where('Line', $line)->sum('tr_qty_loc');
        $standard = StandardProduction::where('line', $line)->sum('total');

        // Mengambil data untuk setiap shift
        $shift1 = $this->getShiftData($line, 'shift1');
        $shift2 = $this->getShiftData($line, 'shift2');
        $shift3 = $this->getShiftData($line, 'shift3');

        return (object) [
            'actual' => $actual,
            'standard' => $standard,
            'shift1' => $shift1,
            'shift2' => $shift2,
            'shift3' => $shift3
        ];
    }

    private function getComparison($lastMonth, $thisMonth)
    {
        if ($lastMonth == 0) {
            return 'N/A';
        }
        $difference = $thisMonth - $lastMonth;
        $percentage = ($difference / $lastMonth) * 100;
        return $percentage > 0 ? "Up by $percentage%" : "Down by $percentage%";
    }

    public function getBarData(Request $request)
    {
        $month = $request->query('month');
        $week = $request->query('week');

        $query = Production::query();

        if ($month && $week) {
            $query->whereMonth('tr_effdate', $month)
                ->whereBetween('tr_effdate', $this->getWeekDateRange($month, $week));
        } elseif ($month) {
            $query->whereMonth('tr_effdate', $month);
        } elseif ($week) {
            $currentMonth = Carbon::today()->month;
            $query->whereMonth('tr_effdate', $currentMonth)
                ->whereBetween('tr_effdate', $this->getWeekDateRange($currentMonth, $week));
        } else {
            $query->whereDate('tr_effdate', Carbon::today());
        }

        $barData = $query->select('Line', DB::raw('SUM(tr_qty_loc) as total_qty'))
            ->groupBy('Line')
            ->get();

        // Log the query and the result
        Log::info('Bar Data Query: ' . $query->toSql());
        Log::info('Bar Data Result: ' . $barData);

        $standardData = StandardProduction::select('line as Line', DB::raw('SUM(total) as total'))
            ->groupBy('line')
            ->get();

        return response()->json([
            'labels' => $barData->pluck('Line'),
            'actual_qty' => $barData->pluck('total_qty'),
            'standard_qty' => $standardData->pluck('total')
        ]);
    }

    private function getWeekDateRange($month, $week)
    {
        $year = now()->year;
        $start = null;
        $end = null;

        switch ($week) {
            case 1:
                $start = Carbon::create($year, $month, 1);
                $end = Carbon::create($year, $month, 7);
                break;
            case 2:
                $start = Carbon::create($year, $month, 8);
                $end = Carbon::create($year, $month, 14);
                break;
            case 3:
                $start = Carbon::create($year, $month, 15);
                $end = Carbon::create($year, $month, 21);
                break;
            case 4:
                $start = Carbon::create($year, $month, 22);
                $end = Carbon::create($year, $month, 28);
                break;
            case 5:
                $start = Carbon::create($year, $month, 29);
                $end = Carbon::create($year, $month, Carbon::create($year, $month)->endOfMonth()->day);
                break;
        }

        return [$start, $end];
    }

    private function getShiftData($line, $shift)
    {
        return Production::where('Line', $line)
            ->where('shift', $shift) // Pastikan ini sesuai dengan nilai di database
            ->whereDate('tr_effdate', Carbon::today())
            ->sum('Weight_in_KG');
    }

    public function filterData(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return ['data' => [], 'grandTotal' => 0]; // Kembalikan array kosong jika tanggal tidak valid
        }

        $data = Production::whereDate('tr_effdate', $date)
            ->select('line', 'shift', DB::raw('SUM(Weight_in_KG) as total_weight'))
            ->groupBy('line', 'shift')
            ->get(); // Pastikan ini mengembalikan koleksi

        $grandTotal = $data->sum('total_weight');

        // Kembalikan data sebagai array
        return ['data' => $data, 'grandTotal' => $grandTotal];
    }

}
