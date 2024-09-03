<?php

namespace App\Http\Controllers\QAD;

use App\Http\Controllers\Controller;
use App\Models\QAD\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
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

    public function getEmployees()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <getemployee xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                    <ip_domain>'.$domain.'</ip_domain>
                </getemployee>
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

        $qdocResult = (string) $xmlResp->xpath('//ns:getemployeeResponse/ns:opOk')[0];

        $employees = $xmlResp->xpath('//ns:getemployeeResponse/ns:ttTable/ns:ttTableRow');
        $jumlahItemBaru = 0;

        if ($qdocResult == 'true') {
            foreach ($employees as $item) {
                $emp_addr = (string) $item->tt_emp_addr;
                if (Employee::where('emp_addr', $emp_addr)->exists()) {
                    continue;
                }

                $newEmployee = new Employee();
                $newEmployee->emp_addr = $emp_addr;
                $newEmployee->emp_sort = (string) $item->tt_emp_sort;
                $newEmployee->emp_city = (string) $item->tt_emp_city;
                $newEmployee->emp_country = (string) $item->tt_emp_country;
                $newEmployee->emp_active = (string) $item->tt_emp_active;
                $newEmployee->emp_emp_date = (string) $item->tt_emp_emp_date;

                $newEmployee->save();
                $jumlahItemBaru++;
            }
            session(['toastMessage' => 'Data berhasil disimpan. Jumlah item baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
        } else {
            // Set error toast message
            session(['toastMessage' => 'Gagal mengambil data dari server.', 'toastType' => 'error']);
        }

        return redirect()->back();
    }
}
