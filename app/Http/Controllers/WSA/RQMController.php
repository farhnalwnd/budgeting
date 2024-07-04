<?php

namespace App\Http\Controllers\WSA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Approver;
use App\Models\CostCenter;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Item;
use App\Models\RequisitionDetail;
use App\Models\RequisitionMaster;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\PrNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use PDF;

class RQMController extends Controller
{

    public function index()
    {
        $approver = Approver::all();
        $suppliers = Supplier::all();
        $cost = CostCenter::all();
        $employees = Employee::all();
        $currency = Currency::all();
        return view('page.requisition-maintenance', \compact('suppliers', 'cost', 'approver', 'employees', 'currency'));
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

    public function getpr()
    {
        $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
        $timeout = 10;
        $domain = 'SMII';
        $qdocRequest =
            '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
        <Body>
            <getlastnumber xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa"/>
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
            if (curl_errno($curl)) {
                return response()->json(['error' => 'Failed to connect to ERP'], 500);
            }
            curl_close($curl);
        }

        if (is_bool($qdocResponse)) {
            return response()->json(['error' => 'Invalid response from ERP'], 500);
        }

        libxml_use_internal_errors(true);
        $xmlResp = simplexml_load_string($qdocResponse);
        if ($xmlResp === false) {
            foreach (libxml_get_errors() as $error) {
                // Log the error or handle it as needed
            }
            libxml_clear_errors();
            return response()->json(['error' => 'Failed to parse XML'], 500);
        }

        $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');
        $outNumber = (string) $xmlResp->xpath('//ns:outnumber')[0];

        if (isset($outNumber)) {
            $outNumber = (string) $outNumber;
            $this->updateNumber($outNumber);
            return response()->json(['prNumber' => $outNumber]);
        }

        return response()->json(['error' => 'No PR number found'], 500);
    }

    public function updateNumber($outNumber)
    {
        $qxUrl = 'http://smii.qad:24079/qxi/services/QdocWebService';
        $timeout = 10;
        $domain = 'SMII';

        $outNumberNew = $outNumber + 1;

        $qdocHead = '<soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
        xmlns:qcom="urn:schemas-qad-com:xml-services:common"
        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <soapenv:Header>
          <wsa:Action/>
          <wsa:To>urn:services-qad-com:Training Andika</wsa:To>
          <wsa:MessageID>urn:services-qad-com::Training Andika</wsa:MessageID>
          <wsa:ReferenceParameters>
            <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
          </wsa:ReferenceParameters>
          <wsa:ReplyTo>
            <wsa:Address>urn:services-qad-com:</wsa:Address>
            </wsa:ReplyTo>
            </soapenv:Header>
        <soapenv:Body>
          <maintainRequisitionControl>
            <qcom:dsSessionContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>domain</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>scopeTransaction</qcom:propertyName>
                <qcom:propertyValue>false</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>version</qcom:propertyName>
                <qcom:propertyValue>ERP3_1</qcom:propertyValue>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
                <qcom:propertyValue>false</qcom:propertyValue>
              </qcom:ttContext>
              <!--
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>username</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>password</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              -->
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>action</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>entity</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>email</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
              <qcom:ttContext>
                <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                <qcom:propertyName>emailLevel</qcom:propertyName>
                <qcom:propertyValue/>
              </qcom:ttContext>
            </qcom:dsSessionContext>
            <dsRequisitionControl>
              <requisitionControl>';

        $qdocbody = ' <operation>M</operation>
        <rqfNbr>' . $outNumberNew . '</rqfNbr>';

        $qdocfoot = ' </requisitionControl>
                         </dsRequisitionControl>
                        </maintainRequisitionControl>
                            </soapenv:Body>
                            </soapenv:Envelope>';

        $qdocRequest = $qdocHead . $qdocbody . $qdocfoot;

        $curlOptions = array(
            CURLOPT_URL => $qxUrl,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout + 120,
            CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
            CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $getInfo = '';
        $curlErrno = '';
        $curlError = '';
        $httpCode = '';
        $qdocResponse = '';

        $curl = curl_init();
        if ($curl) {
            curl_setopt_array($curl, $curlOptions);
            $qdocResponse = curl_exec($curl);
            $curlErrno = curl_errno($curl);
            $curlError = curl_error($curl);
            $first = true;
            foreach (curl_getinfo($curl) as $key => $value) {
                if (gettype($value) != 'array') {
                    if (!$first) $getInfo .= ", ";
                    $getInfo = $getInfo . $key . '=>' . $value;
                    $first = false;
                    if ($key == 'http_code') $httpCode = $value;
                }
            }
            curl_close($curl);
        }

        if (is_bool($qdocResponse)) {
            return [false, 'Koneksi qxtend bermasalah'];
        }

        if (empty($qdocResponse)) {
            Log::channel('custom')->error('Respon dari CURL kosong atau tidak valid');
            return [false, 'Respon dari CURL kosong atau tidak valid'];
        }

        $xmlResp = simplexml_load_string($qdocResponse);
        if ($xmlResp === false) {
            Log::channel('custom')->error('Gagal mengurai XML');
            return [false, 'Gagal mengurai XML'];
        }

        $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
        $qdocResult = $xmlResp->xpath('//ns1:result');
        if ($qdocResult == "success" or $qdocResult == "warning") {
            return [true, $qdocResponse];
        } else {
            $errorlist = '';
            $xmlResp->registerXPathNamespace('ns3', 'urn:schemas-qad-com:xml-services:common');

            foreach ($xmlResp->xpath('//ns3:tt_msg_desc') as $data) {

                if (str_contains((string)$data[0], 'ERROR:')) {
                    $message = strtok((string)$data[0], '.');
                    $errorlist .= $message . ',';
                }
            }
            $errorlist = rtrim($errorlist, ',');
            return [false, $errorlist];
        }
    }


    public function store(Request $request)
    {

        // \dd($request->all());
        // Log data received from the request
        Log::channel('custom')->info('Received request data: ' . json_encode($request->all()));

        // Extract data from request
        $rqmNbr = $request->prNumber;
        $rqmShip = $request->input('rqmShip', '1000');
        $rqmVend = $request->rqmVend;
        $enterby = $request->enterby;
        $rqmReqDate = $request->rqmReqDate;
        $rqmNeedDate = $request->rqmNeedDate;
        $rqmDueDate = $request->rqmDueDate;
        $rqmRqbyUserid = $request->rqmRqbyUserid;
        $rqmEndUserid = $request->rqmEndUserid;
        $rqmReason = $request->rqmReason;
        $rqmRmks = $request->rqmRmks;
        $rqmCc = $request->rqmCc;
        $rqmSite = $request->input('rqmSite', '1000');
        $rqmEntity = $request->input('rqmEntity', 'SMII');
        $rqmCurr = Currency::where('code', $request->rqmCurr)->first()->name;
        $rqmLang = $request->rqmLang;
        $emailOptEntry = $request->input('emailOptEntry', 'R');
        $rqmDirect = $request->input('rqmDirect', false);
        $rqm__log01 = $request->input('rqm__log01', false);
        $rqmAprvStat = $request->input('rqmAprvStat', 'Unapproved');
        $routeToApr = $request->routeToApr;
        $routeToBuyer = $request->routeToBuyer;
        $allInfoCorrect = $request->allInfoCorrect;

        // Create a new requisition master
        $requisitionMaster = RequisitionMaster::create([
            'rqmNbr' => $rqmNbr,
            'rqmShip' => $rqmShip,
            'rqmVend' => $rqmVend,
            'rqmReqDate' => $rqmReqDate,
            'rqmNeedDate' => $rqmNeedDate,
            'rqmDueDate' => $rqmDueDate,
            'rqmRqbyUserid' => $rqmRqbyUserid,
            'enterby' => $enterby,
            'rqmEndUserid' => $rqmEndUserid,
            'rqmReason' => $rqmReason,
            'rqmRmks' => $rqmRmks,
            'rqmCc' => $rqmCc,
            'rqmSite' => $rqmSite,
            'rqmEntity' => $rqmEntity,
            'rqmCurr' => $rqmCurr,
            'rqmLang' => $rqmLang,
            'emailOptEntry' => $emailOptEntry,
            'rqmDirect' => $rqmDirect,
            'rqm__log01' => $rqm__log01,
            'rqmAprvStat' => $rqmAprvStat,
            'routeToApr' => $routeToApr,
            'routeToBuyer' => $routeToBuyer,
            'allInfoCorrect' => $allInfoCorrect,
        ]);



        // Prepare items data for insertion into requisition_deails
        $items = [];
        $rqdParts = $request->input('rqdPart', []);
        $rqdLine = $request->input('rqdLine', []);
        $rqdVends = $request->input('rqdVend', []);
        $rqdReqQtys = $request->input('rqdReqQty', []);
        $rqdUms = $request->input('rqdUm', []);
        $rqdPurCosts = $request->input('rqdPurCost', []);
        $rqdDueDates = $request->input('rqdDueDate', []);
        $rqdNeedDates = $request->input('rqdNeedDate', []);
        $rqdAccts = $request->input('rqdAcct', []);
        $rqdUmConvs = $request->input('rqdUmConv', []);
        $rqdMaxCosts = $request->input('rqdMaxCost', []);
        $lineCmmtss = $request->input('lineCmmts', []);
        $rqdCmts = $request->input('cmtCmmt', []);

        foreach ($rqdParts as $index => $part) {

            $items[] = [
                'rqdNbr' => $rqmNbr,
                'rqdPart' => $part,
                'rqdLine' => $rqdLine[$index],
                'rqdVend' => $rqdVends[$index],
                'rqdReqQty' => $rqdReqQtys[$index],
                'rqdUm' => $rqdUms[$index],
                'rqdPurCost' => $rqdPurCosts[$index],
                'rqdDiscPct' => '0',
                'rqdDueDate' => $rqdDueDates[$index],
                'rqdNeedDate' => $rqdNeedDates[$index],
                'rqdAcct' => $rqdAccts[$index],
                'rqdUmConv' => $rqdUmConvs[$index],
                'rqdMaxCost' => $rqdMaxCosts[$index],
                'lineCmmts' => isset($lineCmmtss[$index]) ? $lineCmmtss[$index] : 'false',
                'rqdCmt' => isset($rqdCmts[$index]) ? $rqdCmts[$index] : '-',
            ];
        }
        foreach ($rqdParts as $index => $rqdPart) {

            // Tambahkan log untuk memeriksa data sebelum disimpan
            Log::info('Data before saving: ' . json_encode([
                'rqdNbr' => $rqmNbr,
                'rqdPart' => $rqdPart,
                'rqdLine' => $rqdLine[$index],
                'rqdVend' => $rqdVends[$index],
                'rqdReqQty' => $rqdReqQtys[$index],
                'rqdUm' => $rqdUms[$index],
                'rqdPurCost' => $rqdPurCosts[$index],
                'rqdDueDate' => $rqdDueDates[$index],
                'rqdNeedDate' => $rqdNeedDates[$index],
                'rqdAcct' => $rqdAccts[$index],
                'rqdUmConv' => $rqdUmConvs[$index],
                'rqdMaxCost' => $rqdMaxCosts[$index],
                'lineCmmts' => isset($lineCmmtss[$index]) ? $lineCmmtss[$index] : 'false',
                'rqdCmt' => isset($rqdCmts[$index]) ? $rqdCmts[$index] : '-',
            ]));
            RequisitionDetail::create([
                'rqdNbr' => $rqmNbr,
                'rqdPart' => $rqdPart,
                'rqdLine' => $rqdLine[$index],
                'rqdVend' => $rqdVends[$index],
                'rqdReqQty' => $rqdReqQtys[$index],
                'rqdUm' => $rqdUms[$index],
                'rqdPurCost' => $rqdPurCosts[$index],
                'rqdDueDate' => $rqdDueDates[$index],
                'rqdNeedDate' => $rqdNeedDates[$index],
                'rqdAcct' => $rqdAccts[$index],
                'rqdUmConv' => $rqdUmConvs[$index],
                'rqdMaxCost' => $rqdMaxCosts[$index],
                'lineCmmts' => isset($lineCmmtss[$index]) ? $lineCmmtss[$index] : 'false',
                'rqdCmt' => isset($rqdCmts[$index]) ? $rqdCmts[$index] : '',
            ]);
        }
        // \dd($items);
        // Call inboundCreate function with necessary data
        $this->inboundCreate($rqmNbr, $enterby, $rqm__log01, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items);


        // Redirect back with success message
        if ($requisitionMaster) {
            $user = Auth::user();

            // Create the notification data
            $data = [
                'title' => 'New PR Created',
                'message' => $rqmNbr . ' has been created by ' . $enterby,
            ];

            Notification::send($user, new PrNotification($data)); // Pass the user ID as an argument
            return redirect()->back()->with('success', 'Permintaan berhasil disimpan.');
        }

        // Redirect back with error message
        if (!$requisitionMaster) {
            return redirect()->back()->with('error', 'Gagal menyimpan permintaan. Silakan coba lagi.');
        }
    }



    public function inboundCreate($rqmNbr, $enterby, $rqm__log01, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items)
    {

        Log::channel('custom')->info('Received request data inbound: ' . json_encode(func_get_args()));

        $qxUrl = 'http://smii.qad:24079/qxi/services/QdocWebService';
        $timeout = 10;
        $domain = 'SMII';

        $qdocHead = '<soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
        xmlns:qcom="urn:schemas-qad-com:xml-services:common"
        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <soapenv:Header>
        <wsa:Action/>
        <wsa:To>urn:services-qad-com:Training Andika</wsa:To>
        <wsa:MessageID>urn:services-qad-com::Training Andika</wsa:MessageID>
        <wsa:ReferenceParameters>
        <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
        </wsa:ReferenceParameters>
        <wsa:ReplyTo>
        <wsa:Address>urn:services-qad-com:</wsa:Address>
        </wsa:ReplyTo>
        </soapenv:Header>
        <soapenv:Body>
        <maintainRequisition>
        <qcom:dsSessionContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>domain</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>scopeTransaction</qcom:propertyName>
        <qcom:propertyValue>false</qcom:propertyValue>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>version</qcom:propertyName>
        <qcom:propertyValue>eB2_2</qcom:propertyValue>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
        <qcom:propertyValue>false</qcom:propertyValue>
        </qcom:ttContext>
        <!--
                <qcom:ttContext>
                  <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                  <qcom:propertyName>username</qcom:propertyName>
                  <qcom:propertyValue/>
                </qcom:ttContext>
                <qcom:ttContext>
                  <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                  <qcom:propertyName>password</qcom:propertyName>
                  <qcom:propertyValue/>
                </qcom:ttContext>
                 -->
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>action</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>entity</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>email</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>emailLevel</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        </qcom:dsSessionContext>
        <dsRequisition>
        <requisition>';

        $qdocBody = '
                        <operation>A</operation>
                        <rqmNbr>' . $rqmNbr . '</rqmNbr>
                        <rqmVend>' . $rqmVend . '</rqmVend>
                        <rqmShip>' . $rqmShip . '</rqmShip>
                        <rqmReqDate>' . $rqmReqDate . '</rqmReqDate>
                        <rqmNeedDate>' . $rqmNeedDate . '</rqmNeedDate>
                        <rqmDueDate>' . $rqmDueDate . '</rqmDueDate>
                        <rqmRqbyUserid>' . $rqmRqbyUserid . '</rqmRqbyUserid>
                        <rqmEndUserid>' . $rqmEndUserid . '</rqmEndUserid>
                        ' . (isset($rqmReason) ? '<rqmReason>' . $rqmReason . '</rqmReason>' : '') . '
                        ' . (isset($rqmRmks) ? '<rqmRmks>' . $rqmRmks . '</rqmRmks>' : '') . '
                        <rqmCc>' . $rqmCc . '</rqmCc>
                        <rqmSite>' . $rqmSite . '</rqmSite>
                        <rqmEntity>' . $rqmEntity . '</rqmEntity>
                        <rqmCurr>' . $rqmCurr . '</rqmCurr>
                        <rqmLang>' . $rqmLang . '</rqmLang>
                        <rqmDirect>' . $rqmDirect . '</rqmDirect>
                        <rqmLog01>' . $rqm__log01 . '</rqmLog01>
                        <emailOptEntry>' . $emailOptEntry . '</emailOptEntry>
                        <hdrCmmts>false</hdrCmmts>
                        <rqmDiscPct>0</rqmDiscPct>
                        <yn>true</yn>
                        <approveOrRoute>true</approveOrRoute>
                        <approvalComments>false</approvalComments>
                        <routeToApr>' . $routeToApr . '</routeToApr>
                        <routeToBuyer>' . $routeToBuyer . '</routeToBuyer>
                        <allInfoCorrect>' . $allInfoCorrect . '</allInfoCorrect>';


        $cdSeq = 1;
        $cmtSeq = 1;
        foreach ($items as $index => $item) {
            $commentParts = $this->splitCommentParts($item['rqdCmt']);
            $qdocBody .= '
                        <lineDetail>
                            <operation>A</operation>
                            <line>' . ($index + 1) . '</line>
                            <lYn>true</lYn>
                            <rqdSite>' . $rqmShip . '</rqdSite>
                            <rqdPart>' . $item['rqdPart'] . '</rqdPart>
                            ' . (isset($item['rqdVend']) ? '<rqdVend>' . $item['rqdVend'] . '</rqdVend>' : '') . '
                            <rqdReqQty>' . $item['rqdReqQty'] . '</rqdReqQty>
                            ' . (isset($item['rqdUm']) ? '<rqdUm>' . $item['rqdUm'] . '</rqdUm>' : '') . '
                            <rqdPurCost>' . $item['rqdPurCost'] . '</rqdPurCost>
                            <rqdDiscPct>0</rqdDiscPct>
                            <rqdDueDate>' . $item['rqdDueDate'] . '</rqdDueDate>
                            <rqdNeedDate>' . $item['rqdNeedDate'] . '</rqdNeedDate>
                            <rqdAcct>' . $item['rqdAcct'] . '</rqdAcct>
                            <rqdUmConv>' . $item['rqdUmConv'] . '</rqdUmConv>
                            <rqdMaxCost>' . $item['rqdMaxCost'] . '</rqdMaxCost>
                            ' . (isset($item['lineCmmts']) ? '<lineCmmts>' . $item['lineCmmts'] . '</lineCmmts>' : '') . '
                            <lineDetailTransComment>
                                <operation>A</operation>
                                <cmtSeq>' . $cmtSeq . '</cmtSeq>
                                <cdLang>us</cdLang>
                                <cdSeq>' . $cdSeq . '</cdSeq>';
            $qdocBody .= $this->buildCommentParts($commentParts);
            $qdocBody .= '
                                <prtOnQuote>true</prtOnQuote>
                                <prtOnSo>true</prtOnSo>
                                <prtOnInvoice>true</prtOnInvoice>
                                <prtOnPacklist>true</prtOnPacklist>
                                <prtOnPo>true</prtOnPo>
                                <prtOnRct>true</prtOnRct>
                                <prtOnRtv>true</prtOnRtv>
                                <prtOnShpr>true</prtOnShpr>
                                <prtOnBol>true</prtOnBol>
                                <prtOnCus>true</prtOnCus>
                                <prtOnProb>true</prtOnProb>
                                <prtOnSchedule>true</prtOnSchedule>
                                <prtOnIsrqst>true</prtOnIsrqst>
                                <prtOnDo>true</prtOnDo>
                                <prtOnIntern>true</prtOnIntern>
                            </lineDetailTransComment>
                        </lineDetail>';
        }

        $qdocFoot = '
            </requisition>
            </dsRequisition>
            </maintainRequisition>
            </soapenv:Body>
            </soapenv:Envelope>';

        $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;

        Log::channel('custom')->info('Constructed Qdoc request: ' . $qdocRequest);

        $curlOptions = array(
            CURLOPT_URL => $qxUrl,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout + 120,
            CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
            CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $getInfo = '';

        $qdocResponse = '';

        $curl = curl_init();
        if ($curl) {
            curl_setopt_array($curl, $curlOptions);
            $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
            //
            $curlErrno = curl_errno($curl);
            $curlError = curl_error($curl);
            $first = true;
            foreach (curl_getinfo($curl) as $key => $value) {
                if (gettype($value) != 'array') {
                    if (!$first) $getInfo .= ", ";
                    $getInfo = $getInfo . $key . '=>' . $value;
                    $first = false;
                    if ($key == 'http_code') $httpCode = $value;
                }
            }
            curl_close($curl);
        }

        if (is_bool($qdocResponse)) {
            return [false, 'Koneksi qxtend bermasalah'];
        }

        if (empty($qdocResponse)) {
            Log::channel('custom')->error('Respon dari CURL kosong atau tidak valid');
            return [false, 'Respon dari CURL kosong atau tidak valid'];
        }

        $xmlResp = simplexml_load_string($qdocResponse);
        if ($xmlResp === false) {
            Log::channel('custom')->error('Gagal mengurai XML');
            return [false, 'Gagal mengurai XML'];
        }

        $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
        $qdocResult = $xmlResp->xpath('//ns1:result');
        $resultValue = (string) $qdocResult[0];

        // Adjusting the comparison to use $resultValue
        if ($resultValue === "success" || $resultValue === "warning") {
            $this->wsaNonPO($rqmNbr, $rqm__log01, $enterby);
            return [true, $qdocResponse];
        } else {
            $errorlist = '';
            $xmlResp->registerXPathNamespace('ns3', 'urn:schemas-qad-com:xml-services:common');
            $qdocMsgData    =  $xmlResp->xpath('//ns3:tt_msg_data');
            $qdocMsgDesc    =  $xmlResp->xpath('//ns3:tt_msg_desc');
            $qdocMsgSev        =  $xmlResp->xpath('//ns3:tt_msg_sev');

            foreach ($xmlResp->xpath('//ns3:tt_msg_desc') as $data) {

                if (str_contains((string)$data[0], 'ERROR:')) {
                    $message = strtok((string)$data[0], '.');
                    $errorlist .= $message . ',';
                }
            }
            $errorlist = rtrim($errorlist, ',');
            return [false, $errorlist];
        }
    }



    public function requisitionBrowser()
    {
        $rqmbrowsers  = RequisitionMaster::with('rqdDets')->get();
        // \dd($rqmbrowsers);
        return \view('page.requisition-browser', \compact('rqmbrowsers'));
    }

    public function wsaNonPO($rqmNbr, $rqm__log01, $enterby)
    {
        try {

            Log::info('rqmNbr: ' . $rqmNbr);
            Log::info('rqdLine: ' . $rqm__log01);
            Log::info('enterby: ' . $enterby);

            // Define SOAP envelope
            $soapEnvelope = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <updateNoPoUser xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                        <noPR>' . $rqmNbr . '</noPR>
                        <nonPO>' . $rqm__log01 . '</nonPO>
                        <userBy>' . $enterby . '</userBy>
                    </updateNoPoUser>
                </Body>
                            </Envelope>';

            Log::channel('custom')->info('SOAP Request: ' . $soapEnvelope);

            // CURL options
            $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
            $timeout = 10;

            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout + 5,
                CURLOPT_HTTPHEADER => $this->httpHeader($soapEnvelope),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $soapEnvelope),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );


            // Initialize CURL
            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);
                Log::info('CURL Response: ' . $qdocResponse);

                // Check for CURL errors
                if (curl_errno($curl)) {
                    Log::error('CURL Error: ' . curl_error($curl));
                    return response()->json(['error' => 'Failed to connect to ERP'], 500);
                }
                curl_close($curl);
            } else {
                Log::error('CURL initialization failed');
                return response()->json(['error' => 'Failed to initialize CURL'], 500);
            }

            // Handle SOAP response
            libxml_use_internal_errors(true);
            $xmlResp = simplexml_load_string($qdocResponse);
            if ($xmlResp === false) {
                foreach (libxml_get_errors() as $error) {
                    Log::error('XML Parsing Error: ' . $error->message);
                }
                libxml_clear_errors();
                return response()->json(['error' => 'Failed to parse XML'], 500);
            }

            $xmlResp->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $result = $xmlResp->xpath('//ns:updateNoPoUserResponse/ns:result');

            if (empty($result)) {
                Log::error('Invalid XML response: missing <result> element');
                return response()->json(['error' => 'Invalid XML response: missing <result> element'], 500);
            }

            $isNil = (string) $result[0]['xsi:nil'];

            if ($isNil === 'true') {
                Log::info('Update result: success');
                return response()->json(['success' => 'Berhasil'], 200);
            } else {
                Log::error('Update result: failure');
                return response()->json(['error' => 'Failed to update data'], 500);
            }
        } catch (Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }

    public function edit($rqmNbr)
    {
        $editRqm = RequisitionMaster::with('rqdDets')->find($rqmNbr);
        $approver = Approver::all();
        $suppliers = Supplier::all();
        $cost = CostCenter::all();
        $employees = Employee::all();
        $currency = Currency::all();


        // $rqdDets = $editRqm->rqdDets;
        return view('page.edit-requisition-maintenance', compact('editRqm', 'approver', 'suppliers', 'cost', 'employees','currency'));
    }

    public function update(Request $request)
    {
        \dd($request->all());
        // Log data received from the request
        Log::channel('custom')->info('Received request data update: ' . json_encode($request->all()));

        // Extract data from request
        $rqmNbr = $request->prNumber;
        $rqmShip = $request->input('rqmShip', '1000');
        $rqmVend = $request->rqmVend;
        $enterby = $request->enterby;
        $rqmReqDate = $request->rqmReqDate;
        $rqmNeedDate = $request->rqmNeedDate;
        $rqmDueDate = $request->rqmDueDate;
        $rqmRqbyUserid = $request->rqmRqbyUserid;
        $rqmEndUserid = $request->rqmEndUserid;
        $rqmReason = $request->rqmReason;
        $rqmRmks = $request->rqmRmks;
        $rqmCc = $request->rqmCc;
        $rqmSite = $request->input('rqmSite', '1000');
        $rqmEntity = $request->input('rqmEntity', 'SMII');
        $rqmCurr = Currency::where('code', $request->rqmCurr)->first()->name;
        $rqmLang = $request->rqmLang;
        $emailOptEntry = $request->input('emailOptEntry', 'R');
        $rqmDirect = $request->input('rqmDirect', false);
        $rqm__log01 = $request->input('rqm__log01', false);
        $rqmAprvStat = $request->input('rqmAprvStat', 'Unapproved');
        $routeToApr = $request->routeToApr;
        $routeToBuyer = $request->routeToBuyer;
        $allInfoCorrect = $request->allInfoCorrect;

        // Check if a record with the same rqmNbr value already exists
        $existingRecord = RequisitionMaster::where('rqmNbr', $rqmNbr)->first();

        if ($existingRecord) {
            // Update the existing record
            Log::channel('custom')->info('Updating RequisitionMaster: ' . $rqmNbr);
            $existingRecord->update([
                'rqmShip' => $rqmShip,
                'rqmVend' => $rqmVend,
                'enterby' => $enterby,
                'rqmReqDate' => $rqmReqDate,
                'rqmNeedDate' => $rqmNeedDate,
                'rqmDueDate' => $rqmDueDate,
                'rqmRqbyUserid' => $rqmRqbyUserid,
                'rqmEndUserid' => $rqmEndUserid,
                'rqmReason' => $rqmReason,
                'rqmRmks' => $rqmRmks,
                'rqmCc' => $rqmCc,
                'rqmSite' => $rqmSite,
                'rqmEntity' => $rqmEntity,
                'rqmCurr' => $rqmCurr,
                'rqmLang' => $rqmLang,
                'emailOptEntry' => $emailOptEntry,
                'rqmDirect' => $rqmDirect,
                'rqm__log01' => $rqm__log01,
                'rqmAprvStat' => $rqmAprvStat,
                'routeToApr' => $routeToApr,
                'routeToBuyer' => $routeToBuyer,
                'allInfoCorrect' => $allInfoCorrect,
            ]);
        } else {
            // Create a new record if it doesn't exist
            Log::channel('custom')->info('Creating new RequisitionMaster: ' . $rqmNbr);
            $existingRecord = RequisitionMaster::create([
                'rqmNbr' => $rqmNbr,
                'rqmShip' => $rqmShip,
                'rqmVend' => $rqmVend,
                'enterby' => $enterby,
                'rqmReqDate' => $rqmReqDate,
                'rqmNeedDate' => $rqmNeedDate,
                'rqmDueDate' => $rqmDueDate,
                'rqmRqbyUserid' => $rqmRqbyUserid,
                'rqmEndUserid' => $rqmEndUserid,
                'rqmReason' => $rqmReason,
                'rqmRmks' => $rqmRmks,
                'rqmCc' => $rqmCc,
                'rqmSite' => $rqmSite,
                'rqmEntity' => $rqmEntity,
                'rqmCurr' => $rqmCurr,
                'rqmLang' => $rqmLang,
                'emailOptEntry' => $emailOptEntry,
                'rqmDirect' => $rqmDirect,
                'rqm__log01' => $rqm__log01,
                'rqmAprvStat' => $rqmAprvStat,
                'routeToApr' => $routeToApr,
                'routeToBuyer' => $routeToBuyer,
                'allInfoCorrect' => $allInfoCorrect,
            ]);
        }

        // Prepare items data for insertion into requisition_details
        $items = [];
        $rqdIds = $request->input('rqdId', []);
        $rqdLine = $request->input('rqdLine', []);
        $rqdParts = $request->input('rqdPart', []);
        $rqdVends = $request->input('rqdVend', []);
        $rqdReqQtys = $request->input('rqdReqQty', []);
        $rqdUms = $request->input('rqdUm', []);
        $rqdPurCosts = $request->input('rqdPurCost', []);
        $rqdDueDates = $request->input('rqdDueDate', []);
        $rqdNeedDates = $request->input('rqdNeedDate', []);
        $rqdAccts = $request->input('rqdAcct', []);
        $rqdUmConvs = $request->input('rqdUmConv', []);
        $rqdMaxCosts = $request->input('rqdMaxCost', []);
        $lineCmmtss = $request->input('lineCmmts', []);
        $rqdCmtss = $request->input('cmtCmmt', []);

        foreach ($rqdParts as $index => $part) {
            $items[] = [
                'rqdNbr' => $rqmNbr,
                'rqdLine' => $rqdLine[$index] ?? null,
                'rqdPart' => $part,
                'rqdVend' => $rqdVends[$index] ?? null,
                'rqdReqQty' => $rqdReqQtys[$index] ?? null,
                'rqdUm' => $rqdUms[$index] ?? null,
                'rqdPurCost' => $rqdPurCosts[$index] ?? null,
                'rqdDiscPct' => '0',
                'rqdDueDate' => $rqdDueDates[$index] ?? null,
                'rqdNeedDate' => $rqdNeedDates[$index] ?? null,
                'rqdAcct' => $rqdAccts[$index] ?? null,
                'rqdUmConv' => $rqdUmConvs[$index] ?? null,
                'rqdMaxCost' => $rqdMaxCosts[$index] ?? null,
                'lineCmmts' => $lineCmmtss[$index] ?? 'false',
                'rqdCmt' => $rqdCmtss[$index] ?? '-',
            ];
        }

        // Get all rqdIds associated with the requisition number $rqmNbr from the database
        $existingRqdIds = RequisitionDetail::where('rqdNbr', $rqmNbr)->pluck('id')->toArray();

        // Determine rqdIds that are in the database but not in the request
        $rqdIdsToDelete = array_diff($existingRqdIds, $rqdIds);

        // Delete requisition details that are no longer in the request
        if (!empty($rqdIdsToDelete)) {
            RequisitionDetail::whereIn('id', $rqdIdsToDelete)->delete();
            Log::channel('custom')->info('Deleted requisition details not in the request: ' . json_encode($rqdIdsToDelete));
        }

        // Update existing requisition details
        foreach ($rqdIds as $index => $rqdId) {
            if (!empty($rqdId)) {
                RequisitionDetail::where('id', $rqdId)->update([
                    'rqdLine' => $rqdLine[$index] ?? null,
                    'rqdPart' => $rqdParts[$index] ?? null,
                    'rqdVend' => $rqdVends[$index] ?? null,
                    'rqdReqQty' => $rqdReqQtys[$index] ?? null,
                    'rqdUm' => $rqdUms[$index] ?? null,
                    'rqdPurCost' => $rqdPurCosts[$index] ?? null,
                    'rqdDiscPct' => '0',
                    'rqdDueDate' => $rqdDueDates[$index] ?? null,
                    'rqdNeedDate' => $rqdNeedDates[$index] ?? null,
                    'rqdAcct' => $rqdAccts[$index] ?? null,
                    'rqdUmConv' => $rqdUmConvs[$index] ?? null,
                    'rqdMaxCost' => $rqdMaxCosts[$index] ?? null,
                    'lineCmmts' => $lineCmmtss[$index] ?? 'false',
                    'rqdCmt' => $rqdCmtss[$index] ?? '-',
                ]);
            } else {
                // Create a new requisition detail if it doesn't exist
                RequisitionDetail::create($items[$index]);
                Log::channel('custom')->info('Created new requisition detail for item: ' . json_encode($items[$index]));
            }
        }

        // Send the response to the API
        Log::channel('custom')->info('Sending request to RQMOutbound API: ' . json_encode($request->all()));
        $this->inboundUpdate($rqmNbr, $enterby, $rqm__log01, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items);
        // Return a response indicating success or failure
        return redirect()->route('rqm.browser');
    }


    public function inboundUpdate($rqmNbr, $enterby, $rqm__log01, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items)
    {

        Log::channel('custom')->info('Received request data inbound updated: ' . json_encode(func_get_args()));

        $qxUrl = 'http://smii.qad:24079/qxi/services/QdocWebService';
        $timeout = 10;
        $domain = 'SMII';

        $qdocHead = '<soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services"
        xmlns:qcom="urn:schemas-qad-com:xml-services:common"
        xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <soapenv:Header>
        <wsa:Action/>
        <wsa:To>urn:services-qad-com:Training Andika</wsa:To>
        <wsa:MessageID>urn:services-qad-com::Training Andika</wsa:MessageID>
        <wsa:ReferenceParameters>
        <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
        </wsa:ReferenceParameters>
        <wsa:ReplyTo>
        <wsa:Address>urn:services-qad-com:</wsa:Address>
        </wsa:ReplyTo>
        </soapenv:Header>
        <soapenv:Body>
        <maintainRequisition>
        <qcom:dsSessionContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>domain</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>scopeTransaction</qcom:propertyName>
        <qcom:propertyValue>false</qcom:propertyValue>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>version</qcom:propertyName>
        <qcom:propertyValue>eB2_2</qcom:propertyValue>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
        <qcom:propertyValue>false</qcom:propertyValue>
        </qcom:ttContext>
        <!--
                <qcom:ttContext>
                  <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                  <qcom:propertyName>username</qcom:propertyName>
                  <qcom:propertyValue/>
                </qcom:ttContext>
                <qcom:ttContext>
                  <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                  <qcom:propertyName>password</qcom:propertyName>
                  <qcom:propertyValue/>
                </qcom:ttContext>
                 -->
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>action</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>entity</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>email</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        <qcom:ttContext>
        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
        <qcom:propertyName>emailLevel</qcom:propertyName>
        <qcom:propertyValue/>
        </qcom:ttContext>
        </qcom:dsSessionContext>
        <dsRequisition>
        <requisition>';

        $qdocBody = '
                        <operation>M</operation>
                        <rqmNbr>' . $rqmNbr . '</rqmNbr>
                        <rqmVend>' . $rqmVend . '</rqmVend>
                        <rqmShip>' . $rqmShip . '</rqmShip>
                        <rqmReqDate>' . $rqmReqDate . '</rqmReqDate>
                        <rqmNeedDate>' . $rqmNeedDate . '</rqmNeedDate>
                        <rqmDueDate>' . $rqmDueDate . '</rqmDueDate>
                        <rqmRqbyUserid>' . $rqmRqbyUserid . '</rqmRqbyUserid>
                        <rqmEndUserid>' . $rqmEndUserid . '</rqmEndUserid>
                        <rqmReason>' . $rqmReason . '</rqmReason>
                        <rqmRmks>' . $rqmRmks . '</rqmRmks>
                        <rqmCc>' . $rqmCc . '</rqmCc>
                         <rqmLog01>' . $rqm__log01 . '</rqmLog01>
                        <rqmSite>' . $rqmSite . '</rqmSite>
                        <rqmEntity>' . $rqmEntity . '</rqmEntity>
                        <rqmCurr>' . $rqmCurr . '</rqmCurr>
                        <rqmLang>' . $rqmLang . '</rqmLang>
                        <rqmDirect>' . $rqmDirect . '</rqmDirect>
                        <emailOptEntry>' . $emailOptEntry . '</emailOptEntry>
                        <hdrCmmts>false</hdrCmmts>
                        <rqmDiscPct>0</rqmDiscPct>
                        <yn>true</yn>
                        <approveOrRoute>true</approveOrRoute>
                        <approvalComments>false</approvalComments>
                        <routeToApr>' . $routeToApr . '</routeToApr>
                        <routeToBuyer>' . $routeToBuyer . '</routeToBuyer>
                        <allInfoCorrect>' . $allInfoCorrect . '</allInfoCorrect>';


        $cdSeq = 1;
        $cmtSeq = 1;
        foreach ($items as $item) {
            $operation = 'M'; // Default operation is 'M'
            // Check if the item already exists
            $existingItem = RequisitionDetail::where('rqdLine', $item['rqdLine'])->first();
            if ($existingItem) {
                $operation = 'A'; // Change operation to 'A' if item exists

                $commentParts = $this->splitCommentParts($item['rqdCmt']);
            }
            $qdocBody .= '
                        <lineDetail>
                        <operation>' . $operation . '</operation>
                        <line>' . $item['rqdLine'] . '</line>
                            <lYn>true</lYn>
                            <rqdSite>' . $rqmShip . '</rqdSite>
                            <rqdPart>' . $item['rqdPart'] . '</rqdPart>
                            <rqdVend>' . $item['rqdVend'] . '</rqdVend>
                            <rqdReqQty>' . $item['rqdReqQty'] . '</rqdReqQty>
                            <rqdUm>' . $item['rqdUm'] . '</rqdUm>
                            <rqdPurCost>' . $item['rqdPurCost'] . '</rqdPurCost>
                            <rqdDiscPct>0</rqdDiscPct>
                            <rqdDueDate>' . $item['rqdDueDate'] . '</rqdDueDate>
                            <rqdNeedDate>' . $item['rqdNeedDate'] . '</rqdNeedDate>
                            <rqdAcct>' . $item['rqdAcct'] . '</rqdAcct>
                            <rqdUmConv>' . $item['rqdUmConv'] . '</rqdUmConv>
                            <rqdMaxCost>' . $item['rqdMaxCost'] . '</rqdMaxCost>
                            <lineCmmts>' . $item['lineCmmts'] . '</lineCmmts>
                            <lineDetailTransComment>
                                <operation>A</operation>
                                <cmtSeq>' . $cmtSeq . '</cmtSeq>
                                <cdLang>us</cdLang>
                                <cdSeq>' . $cdSeq . '</cdSeq>';
            $qdocBody .= $this->buildCommentParts($commentParts);
            $qdocBody .= '
                                <prtOnQuote>true</prtOnQuote>
                                <prtOnSo>true</prtOnSo>
                                <prtOnInvoice>true</prtOnInvoice>
                                <prtOnPacklist>true</prtOnPacklist>
                                <prtOnPo>true</prtOnPo>
                                <prtOnRct>true</prtOnRct>
                                <prtOnRtv>true</prtOnRtv>
                                <prtOnShpr>true</prtOnShpr>
                                <prtOnBol>true</prtOnBol>
                                <prtOnCus>true</prtOnCus>
                                <prtOnProb>true</prtOnProb>
                                <prtOnSchedule>true</prtOnSchedule>
                                <prtOnIsrqst>true</prtOnIsrqst>
                                <prtOnDo>true</prtOnDo>
                                <prtOnIntern>true</prtOnIntern>
                            </lineDetailTransComment>
                        </lineDetail>';
        }

        $qdocFoot = '
            </requisition>
            </dsRequisition>
            </maintainRequisition>
            </soapenv:Body>
            </soapenv:Envelope>';

        $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;

        Log::channel('custom')->info('Constructed Qdoc request udpate: ' . $qdocRequest);

        $curlOptions = array(
            CURLOPT_URL => $qxUrl,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout + 120,
            CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
            CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $getInfo = '';

        $qdocResponse = '';

        $curl = curl_init();
        if ($curl) {
            curl_setopt_array($curl, $curlOptions);
            $qdocResponse = curl_exec($curl);           // sending qdocRequest here, the result is qdocResponse.
            //
            $curlErrno = curl_errno($curl);
            $curlError = curl_error($curl);
            $first = true;
            foreach (curl_getinfo($curl) as $key => $value) {
                if (gettype($value) != 'array') {
                    if (!$first) $getInfo .= ", ";
                    $getInfo = $getInfo . $key . '=>' . $value;
                    $first = false;
                    if ($key == 'http_code') $httpCode = $value;
                }
            }
            curl_close($curl);
        }

        if (is_bool($qdocResponse)) {
            return [false, 'Koneksi qxtend bermasalah'];
        }

        if (empty($qdocResponse)) {
            Log::channel('custom')->error('Respon dari CURL kosong atau tidak valid');
            return [false, 'Respon dari CURL kosong atau tidak valid'];
        }

        $xmlResp = simplexml_load_string($qdocResponse);
        if ($xmlResp === false) {
            Log::channel('custom')->error('Gagal mengurai XML');
            return [false, 'Gagal mengurai XML'];
        }

        $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
        $qdocResult = $xmlResp->xpath('//ns1:result');
        $resultValue = (string) $qdocResult[0];

        // Adjusting the comparison to use $resultValue
        if ($resultValue === "success" || $resultValue === "warning") {
            $this->wsaNonPO($rqmNbr, $rqm__log01, $enterby);
            return [true, $qdocResponse];
        } else {
            $errorlist = '';
            $xmlResp->registerXPathNamespace('ns3', 'urn:schemas-qad-com:xml-services:common');
            $qdocMsgData    =  $xmlResp->xpath('//ns3:tt_msg_data');
            $qdocMsgDesc    =  $xmlResp->xpath('//ns3:tt_msg_desc');
            $qdocMsgSev        =  $xmlResp->xpath('//ns3:tt_msg_sev');

            foreach ($xmlResp->xpath('//ns3:tt_msg_desc') as $data) {

                if (str_contains((string)$data[0], 'ERROR:')) {
                    $message = strtok((string)$data[0], '.');
                    $errorlist .= $message . ',';
                }
            }
            $errorlist = rtrim($errorlist, ',');
            return [false, $errorlist];
        }
    }

    public function deleteLine(Request $request)
    {
        try {
            $rqmNbr = $request->input('rqmNbr');
            $rqdLine = $request->input('rqdLine');

            Log::info('rqmNbr: ' . $rqmNbr);
            Log::info('rqdLine: ' . $rqdLine);

            // Define SOAP envelope
            $soapEnvelope = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <deleteLine xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                        <noPR>' . $rqmNbr . '</noPR>
                        <line>' . $rqdLine . '</line>
                    </deleteLine>
                </Body>
            </Envelope>';

            Log::info('SOAP Request: ' . $soapEnvelope);

            // CURL options
            $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
            $timeout = 10;

            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout + 5,
                CURLOPT_HTTPHEADER => $this->httpHeader($soapEnvelope),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $soapEnvelope),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );

            // Initialize CURL
            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);
                Log::info('CURL Response: ' . $qdocResponse);

                // Check for CURL errors
                if (curl_errno($curl)) {
                    Log::error('CURL Error: ' . curl_error($curl));
                    return response()->json(['error' => 'Failed to connect to ERP'], 500);
                }
                curl_close($curl);
            } else {
                Log::error('CURL initialization failed');
                return response()->json(['error' => 'Failed to initialize CURL'], 500);
            }

            // Handle SOAP response
            if (is_bool($qdocResponse)) {
                Log::error('Invalid response from ERP, response is boolean');
                return response()->json(['error' => 'Invalid response from ERP'], 500);
            }

            libxml_use_internal_errors(true);
            $xmlResp = simplexml_load_string($qdocResponse);
            if ($xmlResp === false) {
                foreach (libxml_get_errors() as $error) {
                    Log::error('XML Parsing Error: ' . $error->message);
                }
                libxml_clear_errors();
                return response()->json(['error' => 'Failed to parse XML'], 500);
            }

            $xmlResp->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $result = $xmlResp->xpath('//ns:deleteLineResponse/ns:result');

            if (empty($result)) {
                Log::error('Invalid XML response: missing <result> element');
                return response()->json(['error' => 'Invalid XML response: missing <result> element'], 500);
            }

            $isNil = (string) $result[0]['xsi:nil'];

            if ($isNil === 'true') {
                Log::info('DeleteLine result: success');
                return response()->json(['success' => 'Berhasil'], 200);
            } else {
                Log::error('DeleteLine result: failure');
                return response()->json(['error' => 'Failed to delete line'], 500);
            }
        } catch (Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }

    public function delete(Request $request)
    {
        // dd($request);
        $rqmNbr = $request->input('rqmNbr');
        $this->inboundDelete($rqmNbr);
        $requisitionMaster = RequisitionMaster::find($rqmNbr);
        if ($requisitionMaster) {
            $requisitionMaster->delete();
            return redirect()->back()->with('success', 'Permintaan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Permintaan tidak ditemukan.');
        }
    }

    public function inboundDelete($rqmNbr)
    {
        Log::channel('custom')->info('Received request data: ' . $rqmNbr);

        $qxUrl = 'http://smii.qad:24079/qxi/services/QdocWebService';
        $timeout = 10;
        $domain = 'SMII';

        $qdocHead = '
                    <soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services" xmlns:qcom="urn:schemas-qad-com:xml-services:common" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
            <soapenv:Header>
            <wsa:Action/>
            <wsa:To>urn:services-qad-com:QADERP</wsa:To>
            <wsa:MessageID>urn:services-qad-com::QADERP</wsa:MessageID>
            <wsa:ReferenceParameters>
            <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
            </wsa:ReferenceParameters>
            <wsa:ReplyTo>
            <wsa:Address>urn:services-qad-com:</wsa:Address>
            </wsa:ReplyTo>
            </soapenv:Header>
            <soapenv:Body>
            <maintainRequisition>
            <qcom:dsSessionContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>domain</qcom:propertyName>
            <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>scopeTransaction</qcom:propertyName>
            <qcom:propertyValue>false</qcom:propertyValue>
            </qcom:ttContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>version</qcom:propertyName>
            <qcom:propertyValue>eB2_2</qcom:propertyValue>
            </qcom:ttContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
            <qcom:propertyValue>false</qcom:propertyValue>
            </qcom:ttContext>
            <!--
                    <qcom:ttContext>
                    <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                    <qcom:propertyName>username</qcom:propertyName>
                    <qcom:propertyValue/>
                    </qcom:ttContext>
                    <qcom:ttContext>
                    <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                    <qcom:propertyName>password</qcom:propertyName>
                    <qcom:propertyValue/>
                    </qcom:ttContext>
                    -->
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>action</qcom:propertyName>
            <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>entity</qcom:propertyName>
            <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>email</qcom:propertyName>
            <qcom:propertyValue/>
            </qcom:ttContext>
            <qcom:ttContext>
            <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
            <qcom:propertyName>emailLevel</qcom:propertyName>
            <qcom:propertyValue/>
            </qcom:ttContext>
            </qcom:dsSessionContext>
            <dsRequisition>
            <requisition>';

        $qdocBody = '
            <operation>R</operation>
            <rqmNbr>' . $rqmNbr . '</rqmNbr>';

        $qdocFoot = '
            </requisition>
            </dsRequisition>
            </maintainRequisition>
            </soapenv:Body>
            </soapenv:Envelope>';

        $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;

        Log::channel('custom')->info('Constructed Qdoc delete: ' . $qdocRequest);

        $curlOptions = array(
            CURLOPT_URL => $qxUrl,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout + 120,
            CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
            CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $getInfo = '';

        $qdocResponse = '';

        $curl = curl_init();
        if ($curl) {
            curl_setopt_array($curl, $curlOptions);
            curl_setopt($curl, CURLOPT_VERBOSE, true); // Menambahkan verbose output untuk debugging
            $qdocResponse = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Mendapatkan status HTTP

            if (curl_errno($curl)) {
                Log::channel('custom')->error('Curl error: ' . curl_error($curl));
            } else if ($httpcode != 200) {
                Log::channel('custom')->error('HTTP status code: ' . $httpcode);
            }

            curl_close($curl);
        }

        if (empty($qdocResponse)) {
            Log::channel('custom')->error('Respon dari CURL kosong atau tidak valid');
            return [false, 'Respon dari CURL kosong atau tidak valid'];
        }

        Log::info('Response Body: ' . $qdocResponse); // Log untuk memeriksa respons yang diterima

        $xmlResp = simplexml_load_string($qdocResponse);
        if ($xmlResp === false) {
            Log::channel('custom')->error('Gagal mengurai XML');
            return [false, 'Gagal mengurai XML'];
        }


        $xmlResp->registerXPathNamespace('ns1', 'urn:schemas-qad-com:xml-services');
        $qdocResult = $xmlResp->xpath('//ns1:result');
        if ($qdocResult == "success" or $qdocResult == "warning") {
            return [true, $qdocResponse];
        } else {
            $errorlist = '';
            $xmlResp->registerXPathNamespace('ns3', 'urn:schemas-qad-com:xml-services:common');
            $qdocMsgData    =  $xmlResp->xpath('//ns3:tt_msg_data');
            $qdocMsgDesc    =  $xmlResp->xpath('//ns3:tt_msg_desc');
            $qdocMsgSev        =  $xmlResp->xpath('//ns3:tt_msg_sev');

            foreach ($xmlResp->xpath('//ns3:tt_msg_desc') as $data) {

                if (str_contains((string)$data[0], 'ERROR:')) {
                    $message = strtok((string)$data[0], '.');
                    $errorlist .= $message . ',';
                }
            }
            $errorlist = rtrim($errorlist, ',');
            return [false, $errorlist];
        }
    }

    public function printRequisition($rqmNbr)
    {
        $item = RequisitionMaster::with('supplier', 'rqdDets')->where('rqmNbr', $rqmNbr)->first();

        // Muat tampilan dengan data dan buat PDF dengan orientasi landscape
        return view('page.report-requisition', compact('item'));

        //     $pdf = PDF::loadView('page.report-requisition', compact('item'));
        //   return $pdf->stream('Laporan-Data-Santri.pdf');
    }

    public function getDataMaster()
    {
        return \view('page.wsa-getmstr');
    }

    private function buildCommentParts($commentParts)
    {
        $commentXml = '';

        foreach ($commentParts as $part) {
            if (!empty($part)) {
                $commentXml .= '<cmtCmmt>' . $part . '</cmtCmmt>' . "\n";
            }
        }

        return $commentXml;
    }

    private function splitCommentParts($comment)
    {
        return str_split($comment, 75);
    }
    /* Outbound */
    public function rqmOutbound(Request $request)
    {
        $xmlcontent = $request->getContent(); //mengambil data string
        Log::channel('custom')->info('xmlcontent: ' . $xmlcontent);
        $xmlstring = simplexml_load_string($xmlcontent); //mengubah string menjadi xml
        Log::channel('custom')->info('xmlstring: ' . $xmlstring);
        $datas = $xmlstring->children('soapenv', true)->Body->children('qdoc', true)->TrainingAndikaOutbound->dsRqm_mstr->rqm_mstr;
        Log::channel('custom')->info('datas: ' . $datas);
        Log::channel('custom')->info('datas: ' . json_encode($datas));

        foreach ($datas as $data) {
            $existingRequisitionMaster = RequisitionMaster::where('rqmNbr', $data->rqmNbr)->first();
            if (!$existingRequisitionMaster) {
                $requisitionMaster = RequisitionMaster::create([
                    'rqmNbr' => $data->rqmNbr,
                    'rqmShip' => $data->rqmShip,
                    'rqmVend' => $data->rqmVend,
                    'rqmReqDate' => $data->rqmReqDate,
                    'rqmNeedDate' => $data->rqmNeedDate,
                    'rqmDueDate' => $data->rqmDueDate,
                    'rqmRqbyUserid' => $data->rqmRqbyUserid,
                    'rqmEndUserid' => $data->rqmEndUserid,
                    'rqmReason' => $data->rqmReason,
                    'rqmRmks' => $data->rqmRmks,
                    'rqmCc' => $data->rqmCc,
                    'rqmSite' => $data->rqmSite,
                    'rqmEntity' => $data->rqmEntity,
                    'rqmCurr' => $data->rqmCurr,
                    'rqmLang' => $data->rqmLang,
                    'emailOptEntry' => $data->emailOptEntry,
                    'rqmDirect' => $data->rqmDirect,
                    'rqm__log01' => $data->rqmLog01,
                    'rqmAprvStat' => $data->rqmAprvStat == '1' ? 'Unapproved' : 'Approved',
                    'routeToApr' => $data->routeToApr,
                    'routeToBuyer' => $data->routeToBuyer,
                    'allInfoCorrect' => $data->allInfoCorrect,
                ]);
                $rqd_det = $data->rqd_det;
                $requisitionDetail = RequisitionDetail::create([
                    'rqdNbr' => $rqd_det['rqdNbr'],
                    'rqdLine' => $rqd_det['rqdLine'],
                    'rqdPart' => $rqd_det['rqdPart'],
                    'rqdDesc' => $rqd_det['rqdDesc'],
                    'rqdVend' => $rqd_det['rqdVend'],
                    'rqdReqQty' => $rqd_det['rqdReqQty'],
                    'rqdUm' => $rqd_det['rqdUm'],
                    'rqdPurCost' => $rqd_det['rqdPurCost'],
                    'rqdDiscPct' => $rqd_det['rqdDiscPct'],
                    'rqdDueDate' => $rqd_det['rqdDueDate'],
                    'rqdNeedDate' => $rqd_det['rqdNeedDate'],
                    'rqdAcct' => $rqd_det['rqdAcct'],
                    'rqdUmConv' => $rqd_det['rqdUmConv'],
                    'rqdMaxCost' => $rqd_det['rqdMaxCost'],
                    'rqdDomain' => $rqd_det['rqdDomain'],
                    'rqdStatus ' => $rqd_det['rqdStatus '],
                ]);
            }
        }
    }

    public function bulkDelete(Request $request)
    {
        $rqmNbrs = $request->input('rqmNbrs');

        // Lakukan validasi jika perlu
        if (empty($rqmNbrs)) {
            return response()->json(['message' => 'No items selected for deletion.'], 422);
        }

        try {
            // Hapus item berdasarkan rqmNbr yang dipilih
            RequisitionMaster::whereIn('rqmNbr', $rqmNbrs)->delete();

            foreach ($rqmNbrs as $rqmNbr) {
                $this->inboundDelete($rqmNbr);
            }

            return response()->json(['message' => 'Items successfully deleted.'], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['message' => 'Error deleting items.'], 500);
        }
    }

    public function checkCurr(Request $request)
    {
        try {
            $codecurr = $request->input('rqmCurr');

            // Define SOAP envelope
            $soapEnvelope = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <checkCurr xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                        <codecurr>' . $codecurr . '</codecurr>
                    </checkCurr>
                </Body>
            </Envelope>';

            Log::channel('custom')->info('SOAP Request: ' . $soapEnvelope);

            // CURL options
            $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
            $timeout = 10;

            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout + 5,
                CURLOPT_HTTPHEADER => $this->httpHeader($soapEnvelope),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $soapEnvelope),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );

            // Initialize CURL
            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);
                Log::info('CURL Response: ' . $qdocResponse);

                // Check for CURL errors
                if (curl_errno($curl)) {
                    Log::error('CURL Error: ' . curl_error($curl));
                    return response()->json(['error' => 'Failed to connect to ERP'], 500);
                }
                curl_close($curl);
            } else {
                Log::error('CURL initialization failed');
                return response()->json(['error' => 'Failed to initialize CURL'], 500);
            }

            // Handle SOAP response
            libxml_use_internal_errors(true);
            $xmlResp = simplexml_load_string($qdocResponse);
            if ($xmlResp === false) {
                foreach (libxml_get_errors() as $error) {
                    Log::error('XML Parsing Error: ' . $error->message);
                }
                libxml_clear_errors();
                return response()->json(['error' => 'Failed to parse XML'], 200); // Menggunakan status 200 untuk memberi sinyal ada kesalahan
            }

            $xmlResp->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $result = $xmlResp->xpath('//ns:checkCurrResponse/ns:result');
            $errmsg = $xmlResp->xpath('//ns:checkCurrResponse/ns:errmsg');

            if (empty($result)) {
                Log::error('Invalid XML response: missing <result> element');
                return response()->json(['error' => 'Invalid XML response: missing <result> element'], 200); // Menggunakan status 200 untuk memberi sinyal ada kesalahan
            }

            $isNil = (string) $result[0]['xsi:nil'];

            if ($isNil === 'true') {
                Log::info('Currency is available');
                return response()->json(['success' => 'Currency is available'], 200);
            } else {
                $errorMessage = !empty($errmsg) ? (string) $errmsg[0] : 'Currency not available right now.';
                Log::error('Currency not available - ' . $errorMessage);
                return response()->json(['error' => $errorMessage], 200); // Menggunakan status 200 untuk memberi sinyal ada kesalahan
            }
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while checking currency'], 500);
        }
    }
}
