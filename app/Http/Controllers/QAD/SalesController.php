<?php

namespace App\Http\Controllers\QAD;

use App\Models\QAD\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\QAD\StandardShipment;
use RealRashid\SweetAlert\Facades\Alert;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::all();
        return view('page.dataDashboard.sales-index', compact('sales'));
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

    public function getSalesDashboard()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $batchSize = 1000; // Ukuran batch
        $offset = 0;
        $startDate = '2024-01-01';
        $endDate = '2024-01-7';
        $totalNewItems = 0;

        do {
            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                    <Body>
                        <getsalesdashboard xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                            <ip_domain>'.$domain.'</ip_domain>
                            <ip_batch_size>'.$batchSize.'</ip_batch_size>
                            <ip_offset>'.$offset.'</ip_offset>
                            <ip_start_date>'.$startDate.'</ip_start_date>
                            <ip_end_date>'.$endDate.'</ip_end_date>
                        </getsalesdashboard>
                    </Body>
                </Envelope>';

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
                return redirect()->back()->with('error', 'Gagal menghubungi server.');
            }

            if (!$qdocResponse) {
                return redirect()->back()->with('error', 'Tidak ada respons dari server.');
            }

            $xmlResp = simplexml_load_string($qdocResponse);
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $qdocResult = (string) $xmlResp->xpath('//ns:getsalesdashboardResponse/ns:opOk')[0];

            $invoices = $xmlResp->xpath('//ns:getsalesdashboardResponse/ns:ttTable/ns:ttTableRow');
            $jumlahItemBaru = 0;

            if ($qdocResult == 'true') {
                foreach ($invoices as $item) {
                    $tr_trnbr = (string) $item->tt_tr_trnbr;
                    $tr_effdate = (string) $item->tt_tr_effdate;
                    $tr_ton = (string) $item->tt_ton;
                    $cm_region = (string) $item->tt_cm_region;
                    $cm_rmks = (string) $item->tt_cm_rmks;
                    $code_cmmt = (string) $item->tt_code_cmmt;
                    $pt_desc1 = (string) $item->tt_pt_desc1;
                    $pt_prod_line = (string) $item->tt_pt_prod_line;
                    $pl_desc = (string) $item->tt_pl_desc;
                    $tr_addr = (string) $item->tt_tr_addr;
                    $ad_name = (string) $item->tt_ad_name;
                    $tr_slspsn = (string) $item->tt_tr_slspsn;
                    $sales_name = (string) $item->tt_sales_name;
                    $pt_part = (string) $item->tt_pt_part;
                    $pt_draw = (string) $item->tt_pt_draw;
                    $margin = number_format((float) str_replace(',', '.', (string) $item->tt_margin), 3, '.', '');
                    $value = number_format((float) str_replace(',', '.', (string) $item->tt_value), 3, '.', '');

                    // Buat invoice baru
                    $newInvoice = new Sales();
                    $newInvoice->tr_trnbr = $tr_trnbr;
                    $newInvoice->tr_effdate = $tr_effdate;
                    $newInvoice->tr_ton = $tr_ton;
                    $newInvoice->cm_region = $cm_region;
                    $newInvoice->cm_rmks = $cm_rmks;
                    $newInvoice->code_cmmt = $code_cmmt;
                    $newInvoice->margin = number_format((float) str_replace(',', '.', $margin), 3, '.', '');
                    $newInvoice->value = number_format((float) str_replace(',', '.', $value), 3, '.', '');
                    $newInvoice->pt_desc1 = $pt_desc1;
                    $newInvoice->pt_prod_line = $pt_prod_line;
                    $newInvoice->pl_desc = $pl_desc;
                    $newInvoice->tr_addr = $tr_addr;
                    $newInvoice->ad_name = $ad_name;
                    $newInvoice->tr_slspsn = $tr_slspsn;
                    $newInvoice->sales_name = $sales_name;
                    $newInvoice->pt_part = $pt_part;
                    $newInvoice->pt_draw = $pt_draw;
                    $newInvoice->save();
                    $jumlahItemBaru++;
                }
                $totalNewItems += $jumlahItemBaru;
            } else {
                session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
                return redirect()->back();
            }

            $offset += $batchSize;
        } while (count($invoices) > 0); // Ubah kondisi untuk memastikan loop berjalan sampai semua data diambil

        session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $totalNewItems, 'toastType' => 'success']);
        return redirect()->back();
    }


    // ============================================StandardShipment====================================================

    public function getShipment()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $totalNewItems = 0;
        $batchSize = 1000; // Ukuran batch
        $offset = 0;
        $startDate = '2024-09-01';
        $endDate = \date('Y-m-d');


        do {
            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                    <Body>
                        <getshipment xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                            <tr_domain>'.$domain.'</tr_domain>
                            <ip_start_date>'.$startDate.'</ip_start_date>
                            <ip_end_date>'.$endDate.'</ip_end_date>
                            <ip_batch_size>'.$batchSize.'</ip_batch_size>
                            <ip_offset>'.$offset.'</ip_offset>
                        </getshipment>
                    </Body>
                </Envelope>';

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
                return redirect()->back()->with('error', 'Gagal menghubungi server.');
            }

            if (!$qdocResponse) {
                return redirect()->back()->with('error', 'Tidak ada respons dari server.');
            }

            $xmlResp = simplexml_load_string($qdocResponse);
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $qdocResult = (string) $xmlResp->xpath('//ns:opOk')[0];

            $invoices = $xmlResp->xpath('//ns:getshipmentResponse/ns:ttTrData/ns:ttTrDataRow'); // Ubah path untuk invoices
            $jumlahItemBaru = 0;

            if ($qdocResult == 'true') {
                foreach ($invoices as $item) {
                    $tr_effdate = $item->tr_effdate; // Ambil elemen yang sesuai
                    $tr_ton = $item->tr_ton; // Ambil elemen yang sesuai
                }

                $newInvoice = new StandardShipment();
                $newInvoice->date_shipment = $tr_effdate;
                $newInvoice->ton = $tr_ton;
                $newInvoice->save();
                $totalNewItems += $jumlahItemBaru;
            } else {
                session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
                return redirect()->back();
            }

        } while (count($invoices) > 0); // Ubah kondisi untuk memastikan loop berjalan sampai semua data diambil

        session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $totalNewItems, 'toastType' => 'success']);
        return redirect()->back();
    }


    public function shipmentindex()
    {
        $standardShipment = StandardShipment::all();
        return \view('page.standard.shipment-index',\compact('standardShipment'));
    }

    public function shipmentstore(Request $request)
    {
        $standardShipment = new StandardShipment();
        $standardShipment->date_shipment = $request->date_shipment;
        $standardShipment->ton = $request->ton;
        $standardShipment->save();
        Alert::toast('Standard Shipment Created Successfully','success');
        return  \view('page.standard.shipment-index');
    }

    public function shipmentupdate(Request $request, StandardShipment $standardShipment)
    {
        $standardShipment->date_shipment = $request->date_shipment;
        $standardShipment->ton = $request->ton;
        $standardShipment->save();
        Alert::toast('Standard Shipment Updated Successfully','success');
        return  \view('page.standard.shipment-index');
    }

    public function shipmentdelete(StandardShipment $standardShipment)
    {
        $standardShipment->delete();
        Alert::toast('Standard Shipment Deleted Successfully','success');
        return  \view('page.standard.shipment-index');
    }

    public function dashboardSales()
    {
        return \view('dashboard.dashboardSales');
    }


}
