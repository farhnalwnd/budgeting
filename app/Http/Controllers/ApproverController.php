<?php

namespace App\Http\Controllers;

use App\Models\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
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

    public function getApproverAndStoreMaster()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <getapprovermaster xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                    <ip_domain>' . $domain . '</ip_domain>
                </getapprovermaster>
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

        $qdocResult = (string) $xmlResp->xpath('//ns:getapprovermasterResponse/ns:opOk')[0];

        $approver = $xmlResp->xpath('//ns:getapprovermasterResponse/ns:ttTable/ns:ttTableRow');
        $jumlahItemBaru = 0;

        if ($qdocResult == 'true') {
            foreach ($approver as $item) {
                $oid_rqa_mstr = (string) $item->tt_oid_rqa_mstr;
                if (Approver::where('oid_rqa_mstr', $oid_rqa_mstr)->exists()) {
                    continue;
                }

                $newApprover = new Approver();
                $newApprover->oid_rqa_mstr = $oid_rqa_mstr;
                $newApprover->rqa_cc_from = (string) $item->tt_rqa_cc_from;
                $newApprover->rqa_cc_to = (string) $item->tt_rqa_cc_to;
                $newApprover->rqa_apr = (string) $item->tt_rqa_apr;
                $newApprover->rqa_apr_level = (string) $item->tt_rqa_apr_level;
                $newApprover->rqa_apr_req = (string) $item->tt_rqa_apr_req;
                $newApprover->rqa_start = (string) $item->tt_rqa_start;
                $newApprover->rqa_end = (string) $item->tt_rqa_end;

                $newApprover->save();
                $jumlahItemBaru++;
            }
            session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
        } else {
            // Set error toast message
            session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
        }

        return redirect()->back();
    }

    public function getApprover(Request $request)
    {

        $costCenter = $request->input('costCenter');

        $approver = Approver::where('rqa_cc_from', $costCenter)
            ->orWhere('rqa_cc_to', $costCenter)
            ->first();

        if ($approver) {
            return response()->json(['rqa_apr' => $approver->rqa_apr]);
        } else {
            return response()->json(['rqa_apr' => '']);
        }
    }
}
