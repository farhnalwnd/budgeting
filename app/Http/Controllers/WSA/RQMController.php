<?php

namespace App\Http\Controllers\WSA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Approver;
use App\Models\CostCenter;
use App\Models\Item;
use App\Models\RequisitionDetail;
use App\Models\RequisitionMaster;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RQMController extends Controller
{

    public function index()
    {
        $approver = Approver::all();
        $suppliers = Supplier::all();
        $cost = CostCenter::all();
        return view('page.requisition-maintenance', \compact('suppliers', 'cost', 'approver'));
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
        $rqmCurr = $request->rqmCurr;
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

        // Prepare items data for insertion into requisition_details
        $items = [];
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
        $rqdCmts = $request->input('cmtCmmt', []);

        foreach ($rqdParts as $index => $part) {
            $items[] = [
                'rqdNbr' => $rqmNbr,
                'rqdPart' => $part,
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
                'lineCmmts' => $lineCmmtss[$index],
                'rqdCmt' => $rqdCmts[$index],
            ];
        }
        foreach ($rqdParts as $index => $rqdPart) {
            // Tambahkan log untuk memeriksa data sebelum disimpan
            Log::info('Data before saving: ' . json_encode([
                'rqdNbr' => $rqmNbr,
                'rqdPart' => $rqdPart,
                'rqdVend' => $rqdVends[$index],
                'rqdReqQty' => $rqdReqQtys[$index],
                'rqdUm' => $rqdUms[$index],
                'rqdPurCost' => $rqdPurCosts[$index],
                'rqdDueDate' => $rqdDueDates[$index],
                'rqdNeedDate' => $rqdNeedDates[$index],
                'rqdAcct' => $rqdAccts[$index],
                'rqdUmConv' => $rqdUmConvs[$index],
                'rqdMaxCost' => $rqdMaxCosts[$index],
                'lineCmmts' => $lineCmmtss[$index],
                'rqdCmt' => $rqdCmts[$index],
            ]));
            RequisitionDetail::create([
                'rqdNbr' => $rqmNbr,
                'rqdPart' => $rqdPart,
                'rqdVend' => $rqdVends[$index],
                'rqdReqQty' => $rqdReqQtys[$index],
                'rqdPurCost' => $rqdPurCosts[$index],
                'rqdDueDate' => $rqdDueDates[$index],
                'rqdNeedDate' => $rqdNeedDates[$index],
                'rqdAcct' => $rqdAccts[$index],
                'rqdUmConv' => $rqdUmConvs[$index],
                'rqdMaxCost' => $rqdMaxCosts[$index],
                'lineCmmts' => $lineCmmtss[$index],
                'rqdCmt' => $rqdCmts[$index],
            ]);
        }

        // Call inboundCreate function with necessary data
        $this->inboundCreate($rqmNbr, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items);

        // Redirect back with success message
        if ($requisitionMaster) {
            return redirect()->back()->with('success', 'Permintaan berhasil disimpan.');
        }

        // Redirect back with error message
        if (!$requisitionMaster) {
            return redirect()->back()->with('error', 'Gagal menyimpan permintaan. Silakan coba lagi.');
        }
    }



    public function inboundCreate($rqmNbr, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items)
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
                        <rqmReason>' . $rqmReason . '</rqmReason>
                        <rqmRmks>' . $rqmRmks . '</rqmRmks>
                        <rqmCc>' . $rqmCc . '</rqmCc>
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

        $lineNumber = 1; // Initialize line number for incrementing in the loop
        $cdSeq = 1;
        $cmtSeq = 1;
        foreach ($items as $item) {
            $qdocBody .= '
                        <lineDetail>
                            <operation>A</operation>
                            <line>' . $lineNumber . '</line>
                            <lYn>true</lYn>
                            <rqdSite>' . $rqmShip . '</rqdSite>
                            <rqdPart>' . $item['rqdPart'] . '</rqdPart>
                            <rqdVend>' . $item['rqdVend'] . '</rqdVend>
                            <rqdReqQty>' . $item['rqdReqQty'] . '</rqdReqQty>
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
                                <cdSeq>' . $cdSeq . '</cdSeq>
                                <cmtCmmt>' . $item['rqdCmt'] . '</cmtCmmt>
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
            $lineNumber++;
        }

        $qdocFoot = '
            </requisition>
            </dsRequisition>
            </maintainRequisition>
            </soapenv:Body>
            </soapenv:Envelope>';

        $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;

        Log::channel('custom')->info('Constructed Qdoc request: ' . $qdocRequest);
        Log::channel('custom')->info('Constructed Qdoc body: ' . $qdocBody);

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

    public function getDataMaster()
    {
        return \view('page.wsa-getmstr');
    }

    public function requisitionBrowser()
    {
        $rqmbrowsers  = RequisitionMaster::with('rqdDets')->get();
        // \dd($rqmbrowsers);
        return \view('page.requisition-browser', \compact('rqmbrowsers'));
    }



    public function edit($rqmNbr)
    {
        $editRqm = RequisitionMaster::with('rqdDets')->find($rqmNbr);
        $approver = Approver::all();
        $suppliers = Supplier::all();
        $cost = CostCenter::all();
        // $rqdDets = $editRqm->rqdDets;
        return view('page.edit-requisition-maintenance', compact('editRqm', 'approver', 'suppliers', 'cost'));
    }

    public function update(Request $request)
    {
        // Log data received from the request
        Log::channel('custom')->info('Received request data update: '. json_encode($request->all()));

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
        $rqmCurr = $request->rqmCurr;
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
            // Create a new record
            $requisitionMaster = RequisitionMaster::create([
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
                'rqdNbr' => $request->prNumber,
                'rqdPart' => $part,
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
                'lineCmmts' => $lineCmmtss[$index],
                'rqdCmt' => $rqdCmtss[$index],
            ];
        }

        foreach ($rqdParts as $index => $rqdPart) {
            $existingRecordDetail = RequisitionDetail::where('rqdNbr', $rqmNbr)->where('rqdPart', $rqdPart)->first();
            if ($existingRecordDetail) {
                // Update the existing detail record
                $existingRecordDetail->update([
                    'rqdNbr' => $rqmNbr,
                    'rqdPart' => $rqdPart,
                    'rqdVend' => $rqdVends[$index],
                    'rqdReqQty' => $rqdReqQtys[$index],
                    'rqdPurCost' => $rqdPurCosts[$index],
                    'rqdDueDate' => $rqdDueDates[$index],
                    'rqdNeedDate' => $rqdNeedDates[$index],
                    'rqdAcct' => $rqdAccts[$index],
                    'rqdUmConv' => $rqdUmConvs[$index],
                    'rqdMaxCost' => $rqdMaxCosts[$index],
                    'lineCmmts' => $lineCmmtss[$index],
                    'rqdCmt' => $rqdCmtss[$index],
                ]);
            } else {
                // Create a new detail record
                RequisitionDetail::create([
                    'rqdNbr' => $rqmNbr,
                    'rqdPart' => $rqdPart,
                    'rqdVend' => $rqdVends[$index],
                    'rqdReqQty' => $rqdReqQtys[$index],
                    'rqdPurCost' => $rqdPurCosts[$index],
                    'rqdDueDate' => $rqdDueDates[$index],
                    'rqdNeedDate' => $rqdNeedDates[$index],
                    'rqdAcct' => $rqdAccts[$index],
                    'rqdUmConv' => $rqdUmConvs[$index],
                    'rqdMaxCost' => $rqdMaxCosts[$index],
                    'lineCmmts' => $lineCmmtss[$index],
                    'rqdCmt' => $rqdCmtss[$index],
                ]);
            }
        }

        // Call inboundUpdate function with necessary data
        $this->inboundUpdate($request->prNumber, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items);

        // Redirect back with success message
        if ($existingRecord) {
            return redirect()->route('rqm.browser')->with('success', 'Data berhasil diperbarui.');
        } else {
            return redirect()->route('rqm.browser')->with('error', 'Gagal memperbarui data.');
        }
    }



    public function inboundUpdate($rqmNbr, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items)
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

        $lineNumber = 1; // Initialize line number for incrementing in the loop
        $cdSeq = 1;
        $cmtSeq = 1;
        foreach ($items as $item) {
            $qdocBody .= '
                        <lineDetail>
                            <operation>M</operation>
                            <line>' . $lineNumber . '</line>
                            <lYn>true</lYn>
                            <rqdSite>' . $rqmShip . '</rqdSite>
                            <rqdPart>' . $item['rqdPart'] . '</rqdPart>
                            <rqdVend>' . $item['rqdVend'] . '</rqdVend>
                            <rqdReqQty>' . $item['rqdReqQty'] . '</rqdReqQty>
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
                                <cdSeq>' . $cdSeq . '</cdSeq>
                                <cmtCmmt>' . $item['rqdCmt'] . '</cmtCmmt>
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
            $lineNumber++;
        }

        $qdocFoot = '
            </requisition>
            </dsRequisition>
            </maintainRequisition>
            </soapenv:Body>
            </soapenv:Envelope>';

        $qdocRequest = $qdocHead . $qdocBody . $qdocFoot;

        Log::channel('custom')->info('Constructed Qdoc request: ' . $qdocRequest);
        Log::channel('custom')->info('Constructed Qdoc body: ' . $qdocBody);

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
        $item = RequisitionMaster::with('supplier')->where('rqmNbr', $rqmNbr)->firstOrFail();

        // Load the view with the data and create a PDF
        $pdf = Pdf::loadView('page.report-requisition', compact('item'));
        // Return the PDF for download
        return $pdf->stream();
    }
}
