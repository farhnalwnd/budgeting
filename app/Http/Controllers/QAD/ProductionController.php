<?php

namespace App\Http\Controllers\QAD;

use Illuminate\Http\Request;
use App\Models\QAD\Production;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\QAD\StandardProduction;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\QAD\StandardWarehouseProduction;

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
        $totalNewItems = 0;
        $totalUpdatedItems = 0;

        do {
            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                    <Body>
                        <getproduction xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                            <ip_domain>'.$domain.'</ip_domain>
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



    public function dashboardProduction()
    {
        return \view('dashboard.dashboardProduction');
    }

    // =========================================================StandardWarehouse=====================================================

    public function warehouseindex()
    {
        $standardWarehouse = StandardWarehouseProduction::all();
        return \view('page.standard.warehouse-index',\compact('standardWarehouse'));
    }



    public function warehousestore(Request $request)
    {
        $standardWarehouse = new StandardWarehouseProduction();
        $standardWarehouse->location = $request->location;
        $standardWarehouse->rack = $request->rack;
        $standardWarehouse->temperature = $request->temperature;
        $standardWarehouse->pallet_rack = $request->pallet_rack;
        $standardWarehouse->estimated_tonnage = $request->estimated_tonnage;
        $standardWarehouse->save();
        Alert::toast('Standard Warehouse Production successfully added', 'success');
        return redirect()->back();
    }

    public function warehouseupdate(Request $request, $id)
    {
        $standardWarehouse = StandardWarehouseProduction::findOrFail($id);
        $standardWarehouse->location = $request->location;
        $standardWarehouse->rack = $request->rack;
        $standardWarehouse->temperature = $request->temperature;
        $standardWarehouse->pallet_rack = $request->pallet_rack;
        $standardWarehouse->estimated_tonnage = $request->estimated_tonnage;
        $standardWarehouse->save();
        Alert::toast('Standard Warehouse Production successfully updated', 'success');
        return redirect()->back();
    }

    public function warehousedelete($id)
    {
        $standardWarehouse = StandardWarehouseProduction::findOrFail($id);
        $standardWarehouse->delete();
        Alert::toast('Standard Production successfully deleted', 'success');
        return redirect()->back();
    }

    public function dashboardWarehouse()
    {
        return \view('dashboard.dashboardWarehouse');
    }
}
