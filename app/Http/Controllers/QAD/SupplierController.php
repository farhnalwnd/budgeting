<?php

namespace App\Http\Controllers\QAD;

use App\Http\Controllers\Controller;
use App\Models\QAD\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
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

    public function getSuppliersAndStoreMaster()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <getsuppliermaster xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                    <ip_domain>' . $domain . '</ip_domain>
                </getsuppliermaster>
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

        $qdocResult = (string) $xmlResp->xpath('//ns:getsuppliermasterResponse/ns:opOk')[0];

        $suppliers = $xmlResp->xpath('//ns:getsuppliermasterResponse/ns:ttTable/ns:ttTableRow');
        $jumlahItemBaru = 0;

        if ($qdocResult == 'true') {
            foreach ($suppliers as $item) {
                $vd_addr = (string) $item->tt_vd_addr;
                if (Supplier::where('vd_addr', $vd_addr)->exists()) {
                    continue;
                }

                $newSupplier = new Supplier();
                $newSupplier->vd_addr = $vd_addr;
                $newSupplier->vd_taxable = (string) $item->tt_vd_taxable;
                $newSupplier->vd_sort = (string) $item->tt_vd_sort;
                $newSupplier->ad_name = (string) $item->tt_ad_name;
                $newSupplier->ad_line1 = (string) $item->tt_ad_line1;
                $newSupplier->ad_line2 = (string) $item->tt_ad_line2;
                $newSupplier->ad_line3 = (string) $item->tt_ad_line3;
                $newSupplier->ad_city = (string) $item->tt_ad_city;
                $newSupplier->vd_type = (string) $item->tt_vd_type;
                $newSupplier->vd_cr_term = (string) $item->tt_vd_cr_term;
                $newSupplier->ad_date = (string) $item->tt_ad_date;

                $newSupplier->save();
                $jumlahItemBaru++;
            }
            session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
        } else {
            // Set error toast message
            session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
        }

        return redirect()->back();
    }

    public function getSupplierAjax()
    {
        $suppliers = Supplier::select('vd_addr', 'vd_taxable', 'vd_sort', 'ad_name', 'ad_line1', 'ad_city')->get();
        return response()->json(['data' => $suppliers]);
    }
}
