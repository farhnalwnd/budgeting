<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{


    // public function import(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|file'
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $fileExtension = $request->file('file')->getClientOriginalExtension();

    //     if (!in_array($fileExtension, ['xlsx', 'xls', 'csv'])) {
    //         return redirect()->back()->with('error', 'Unsupported file type. Please upload a valid Excel (xlsx, xls) or CSV file.')->withInput();
    //     }

    //     try {
    //         Excel::import(new Item(), $request->file('file'));
    //         return redirect()->back()->with('success', 'Items imported successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to import items: ' . $e->getMessage());
    //     }
    // }

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

    public function getItemAndStoreMaster()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <getitemmaster xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                    <ip_domain>' . $domain . '</ip_domain>
                </getitemmaster>
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

        $qdocResult = (string) $xmlResp->xpath('//ns:getitemmasterResponse/ns:opOk')[0];

        $items = $xmlResp->xpath('//ns:getitemmasterResponse/ns:ttTable/ns:ttTableRow');
        $jumlahItemBaru = 0;

        if ($qdocResult == 'true') {
            foreach ($items as $item) {
                $pt_part = (string) $item->ttpart;
                if (Item::where('pt_part', $pt_part)->exists()) {
                    continue;
                }

                $newItem = new Item();
                $newItem->pt_part = $pt_part;
                $newItem->pt_rev = (string) $item->ttrev;
                $newItem->pt_desc1 = (string) $item->ttdesc1;
                $newItem->pt_desc2 = (string) $item->ttdesc2;
                $newItem->pt_abc = (string) $item->tt_abc;
                $newItem->pt_drwg_loc = (string) $item->tt_drwg_loc;
                $newItem->pt_status = (string) $item->tt_status;
                $newItem->pt_routing = (string) $item->tt_routing;
                $newItem->pt_bom_code = (string) $item->tt_bom_code;
                $newItem->pt_run = (string) $item->tt_run;
                $newItem->pt_um = (string) $item->tt_um;
                $newItem->pt_taxable = (string) $item->tt_taxable;
                $newItem->pt_net_wt = (string) $item->tt_net_wt;
                $newItem->pt_net_wt_um = (string) $item->tt_net_wt_um;
                $newItem->pt_size = (string) $item->tt_size;
                $newItem->pt_size_um = (string) $item->tt_size_um;
                $newItem->pt_dsgn_grp = (string) $item->tt_dsgn_grp;
                $newItem->pt_prod_line = (string) $item->tt_prod_line;
                $newItem->pt_shelflife = (string) $item->tt_shelflife;
                $newItem->pt_part_type = (string) $item->tt_part_type;
                $newItem->pt_group = (string) $item->tt_group;
                $newItem->pt_draw = (string) $item->tt_draw;
                $newItem->pt_added = (string) $item->tt_added;
                $newItem->pt_buyer = (string) $item->tt_buyer;
                $newItem->pt_promo = (string) $item->tt_promo;
                $newItem->pt_userid = (string) $item->tt_userid;
                $newItem->pt_mod_date = (string) $item->tt_mod_date;

                $newItem->save();
                $jumlahItemBaru++;
            }
            session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
        } else {
            // Set error toast message
            session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
        }

        return redirect()->back();
    }

    public function getItemAjax()
    {
        $items = Item::where('pt_status', '0001')->get();
        return response()->json(['data' => $items]);
    }
}
