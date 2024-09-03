<?php

namespace App\Http\Controllers\QAD;

use App\Http\Controllers\Controller;
use App\Models\QAD\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
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

    public function getAccountAndStoreMaster()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <getaccountmaster xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                    <ip_domain>' . $domain . '</ip_domain>
                </getaccountmaster>
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

        $qdocResult = (string) $xmlResp->xpath('//ns:getaccountmasterResponse/ns:opOk')[0];

        $account = $xmlResp->xpath('//ns:getaccountmasterResponse/ns:ttTable/ns:ttTableRow');
        $jumlahItemBaru = 0;

        if ($qdocResult == 'true') {
            foreach ($account as $item) {
                $ac_code = (string) $item->tt_ac_code;
                if (Account::where('ac_code', $ac_code)->exists()) {
                    continue;
                }

                $newAccount = new Account();
                $newAccount->ac_code = $ac_code;
                $newAccount->ac_curr = (string) $item->tt_ac_curr;
                $newAccount->ac_desc = (string) $item->tt_ac_desc;
                $newAccount->ac_gl_type = (string) $item->tt_ac_gl_type;

                $newAccount->save();
                $jumlahItemBaru++;
            }
            session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
        } else {
            // Set error toast message
            session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
        }

        return redirect()->back();
    }

    public function getAccountAjax()
    {
        $accounts = Account::all(); // Misalnya, mengambil semua akun pembelian dari database
        return response()->json(['data' => $accounts]);
    }
}
