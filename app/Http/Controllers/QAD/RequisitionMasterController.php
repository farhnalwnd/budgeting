<?php

namespace App\Http\Controllers\QAD;

use App\Http\Controllers\Controller;
use App\Mail\PRApproval;
use App\Mail\RequisitionRejected;
use App\Models\QAD\Notification;
use App\Models\QAD\RequisitionApprovalDetail;
use App\Models\QAD\RequisitionMaster;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class RequisitionMasterController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view report requisition', ['only' => ['index', 'report']]);
        $this->middleware('permission:view approval requisition', ['only' => ['approval']]);
        $this->middleware('permission:resend requisition', ['only' => ['resend']]);
    }
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        try {
            Notification::truncate(); // Hapus semua notifikasi
            return response()->json(['message' => 'All notifications deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete notifications.'], 500);
        }
    }

    public function report()
    {
        // Mendapatkan user saat ini
        $currentUser = Auth::user();
        // Memuat relasi department
        $currentUser->load('department');

        // Memeriksa department_name melalui relasi department
        if ($currentUser->department && strtolower($currentUser->department->department_name) === 'all') {
            // Jika department_name adalah 'all', ambil semua RequisitionMaster
            $rqmreports = RequisitionMaster::with('rqdDets', 'user.department')->get();
        } else {
            // Jika tidak, ambil RequisitionMaster yang sesuai dengan department_id user saat ini
            $rqmreports = RequisitionMaster::whereHas('user', function ($query) use ($currentUser) {
                $query->where('department_id', $currentUser->department_id);
            })
                ->with('rqdDets', 'user.department') // Jika diperlukan untuk memuat relasi rqdDets dan department
                ->get();
        }
        return view('page..requisition.report-requisition-maintenance', \compact('rqmreports'));
    }

    public function bulkPrint(Request $request)
    {
        $rqmNbrs = $request->input('rqmNbrs');
        $rqmreports = RequisitionMaster::whereIn('rqmNbr', $rqmNbrs)->get();


        return view('page..requisition.print-requisition-maintenance', compact('rqmreports'));
    }

    public function approval()
    {
        // Mendapatkan user saat ini
        $currentUser = Auth::user();

        // Memeriksa apakah user memiliki role 'super-admin'
        if ($currentUser->hasRole('super-admin')) {
            // Jika user adalah 'super-admin', ambil semua RequisitionMaster
            $rqmapprovals = RequisitionMaster::where('rqmAprvStat', 'Unapproved')
                ->with('rqdDets') // Memuat relasi rqdDets
                ->get();
        } else {
            // Jika tidak, ambil RequisitionMaster yang sesuai dengan routeToApr dan routeToApr adalah username user saat ini
            $rqmapprovals = RequisitionMaster::where('routeToApr', $currentUser->username)
                ->where('rqmAprvStat', 'Unapproved')
                ->where('routeToApr', $currentUser->username) // Memastikan routeToApr adalah username user saat ini
                ->with('rqdDets') // Memuat relasi rqdDets
                ->get();
        }

        return view('page..requisition.approval-requisition-maintenance', \compact('rqmapprovals'));
    }

    public function approveEmail($rqmNbr, $token)
    {
        // Validate and decrypt token
        try {
            $decrypted = Crypt::decryptString($token);
            [$tokenRqmNbr] = explode('|', $decrypted);

            if ($rqmNbr !== $tokenRqmNbr) {
                return redirect()->route('rqm.browser')->with('error', 'Invalid requisition number.');
            }
            $message = 'Requisition approved successfully.';
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('rqm.browser')->with('error', 'Invalid or expired token.');
        }

        $currentUser = Auth::user();
        $rqdaTime = rand(10000, 99999);

        // Retrieve RequisitionMaster and calculate maxExtCostTotal
        $requisition = RequisitionMaster::with('rqdDets')->where('rqmNbr', $rqmNbr)->firstOrFail();
        $maxExtCostTotal = $requisition->rqdDets->sum(function ($detail) use ($requisition) {
            $reqQty = floatval($detail->rqdReqQty);
            $purCost = floatval($detail->rqdPurCost);
            $exRate = $requisition->rqmCurr !== 'IDR' ? floatval($requisition->rqmExRate2) : 1;
            return $reqQty * $purCost * $exRate;
        });

        // Check if the current user has already approved
        if (RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)
            ->where('rqdaAprUserid', $currentUser->username)
            ->exists()
        ) {
            $message = 'You have already approved this requisition.';
            return view('page.requisition.notification-approval', compact('message', 'rqmNbr'));
        }

        // Validation for approval based on cost
        if ($maxExtCostTotal > 5000001) {
            // Check if 'melvin' has already approved
            if ($requisition->routeToApr === $requisition->routeToBuyer) {
                $message = 'You have already approved this requisition.';
                return view('page.requisition.notification-approval', compact('message', 'rqmNbr'));
            }

            // Check if this is the first approval
            $isFirstApproval = !RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)->exists();

            RequisitionApprovalDetail::create([
                'rqdaNbr' => $rqmNbr,
                'rqdaAction' => 1, // Approved
                'rqdaAprUserid' => $requisition->routeToApr,
                'rqdaTime' => $rqdaTime,
            ]);

            // Update RequisitionMaster
            $updateData = [
                'routeToApr' => 'melvin',
                'rqmAprvStat' => 'Unapproved',
            ];
            RequisitionMaster::where('rqmNbr', $rqmNbr)->update($updateData);

            // Generate unique tokens with rqmNbr
            $approvalToken = Crypt::encryptString($rqmNbr . '|' . $requisition->enterby);
            $declineToken = Crypt::encryptString($rqmNbr . '|' . $requisition->enterby);

            // Send email to user 'melvin' if not already sent
            if ($requisition->routeToApr !== 'melvin') {
                $melvinUser = User::where('username', 'melvin')->first();
                if ($melvinUser && $requisition->rqmAprvStat === 'Unapproved') {
                    $dataEmail = [
                        'rqmNbr' => $requisition->rqmNbr,
                        'rqmVend' => $requisition->rqmVend,
                        'rqmReqDate' => $requisition->rqmReqDate,
                        'rqmNeedDate' => $requisition->rqmNeedDate,
                        'rqmDueDate' => $requisition->rqmDueDate,
                        'enterby' => $requisition->enterby,
                        'rqmEndUserid' => $requisition->rqmEndUserid,
                        'rqm__log01' => $requisition->rqm__log01,
                        'rqmCc' => $requisition->rqmCc,
                        'rqmCurr' => $requisition->rqmCurr,
                        'rqmDirect' => $requisition->rqmDirect,
                        'rqmAprvStat' => $requisition->rqmAprvStat,
                        'rqmRmks' => $requisition->rqmRmks,
                        'rqmReason' => $requisition->rqmReason,
                        'rqdDets' => $requisition->rqdDets,
                        'approval_link' => route('rqm.approveEmail', ['rqmNbr' => $requisition->rqmNbr, 'token' => $approvalToken]),
                        'decline_link' => route('rqm.declineEmail', ['rqmNbr' => $requisition->rqmNbr, 'token' => $declineToken]),
                    ];

                    Mail::to($melvinUser->email)->send(new PRApproval($dataEmail));
                }
            }
        } else {
            // Jika tidak ada maka eksekusi create
            RequisitionApprovalDetail::create([
                'rqdaNbr' => $rqmNbr,
                'rqdaAction' => 1, // Approved
                'rqdaAprUserid' => $requisition->routeToApr,
                'rqdaTime' => $rqdaTime,
            ]);

            // Update RequisitionMaster
            $updateData = [
                'routeToApr' => $requisition->routeToBuyer,
                'rqmAprvStat' => 'Approved',
            ];

            RequisitionMaster::where('rqmNbr', $rqmNbr)->update($updateData);

            // Mengirim data ke inboundCreate
            $this->inboundCreate(
                $rqmNbr,
                $requisition->enterby,
                $requisition->rqm__log01,
                $requisition->rqmVend,
                $requisition->rqmShip,
                $requisition->rqmReqDate,
                $requisition->rqmNeedDate,
                $requisition->rqmDueDate,
                $requisition->rqmRqbyUserid,
                $requisition->rqmEndUserid,
                $requisition->rqmReason,
                $requisition->rqmRmks,
                $requisition->rqmSub,
                $requisition->rqmCc,
                $requisition->rqmSite,
                $requisition->rqmEntity,
                $requisition->rqmCurr,
                $requisition->rqmLang,
                $requisition->rqmDirect,
                $requisition->emailOptEntry,
                $requisition->rqmAprvStat,
                $requisition->routeToApr,
                $requisition->routeToBuyer,
                $requisition->allInfoCorrect,
                $requisition->rqdDets->toArray()
            );
        }

        // Additional check for specific approval user
        if ($requisition->routeToApr === 'melvin' && RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)->where('rqdaAprUserid', 'melvin')->exists()) {
            RequisitionMaster::where('rqmNbr', $rqmNbr)->update([
                'routeToApr' => $requisition->routeToBuyer,
                'rqmAprvStat' => 'Approved',
            ]);

            // Mengirim data ke inboundCreate untuk approval kedua
            $this->inboundCreate(
                $rqmNbr,
                $requisition->enterby,
                $requisition->rqm__log01,
                $requisition->rqmVend,
                $requisition->rqmShip,
                $requisition->rqmReqDate,
                $requisition->rqmNeedDate,
                $requisition->rqmDueDate,
                $requisition->rqmRqbyUserid,
                $requisition->rqmEndUserid,
                $requisition->rqmReason,
                $requisition->rqmRmks,
                $requisition->rqmSub,
                $requisition->rqmCc,
                $requisition->rqmSite,
                $requisition->rqmEntity,
                $requisition->rqmCurr,
                $requisition->rqmLang,
                $requisition->rqmDirect,
                $requisition->emailOptEntry,
                $requisition->rqmAprvStat,
                $requisition->routeToApr,
                $requisition->routeToBuyer,
                $requisition->allInfoCorrect,
                $requisition->rqdDets->toArray()
            );
        }

        return view('page.requisition.notification-approval', compact('message', 'rqmNbr'));
    }


    public function approve($rqmNbr)
    {

        $currentUser = Auth::user();
        $rqdaTime = rand(10000, 99999);

        // Retrieve RequisitionMaster and calculate maxExtCostTotal
        $requisition = RequisitionMaster::with('rqdDets')->where('rqmNbr', $rqmNbr)->firstOrFail();
        $maxExtCostTotal = $requisition->rqdDets->sum(function ($detail) use ($requisition) {
            $reqQty = floatval($detail->rqdReqQty);
            $purCost = floatval($detail->rqdPurCost);
            $exRate = $requisition->rqmCurr !== 'IDR' ? floatval($requisition->rqmExRate2) : 1;
            return $reqQty * $purCost * $exRate;
        });

        // Add approval detail
        RequisitionApprovalDetail::create([
            'rqdaNbr' => $rqmNbr,
            'rqdaAction' => 1, // Approved
            'rqdaAprUserid' => $requisition->routeToApr,
            'rqdaTime' => $rqdaTime,
        ]);

        // Update RequisitionMaster based on conditions
        if ($maxExtCostTotal > 5000001) {
            $updateData = [
                'routeToApr' => 'melvin',
                'rqmAprvStat' => 'Unapproved',
            ];
        } else {
            $updateData = [
                'routeToApr' => $requisition->routeToBuyer,
                'rqmAprvStat' => 'Approved',
            ];

            // Mengirim data ke inboundCreate
            $this->inboundCreate(
                $rqmNbr,
                $requisition->enterby,
                $requisition->rqm__log01,
                $requisition->rqmVend,
                $requisition->rqmShip,
                $requisition->rqmReqDate,
                $requisition->rqmNeedDate,
                $requisition->rqmDueDate,
                $requisition->rqmRqbyUserid,
                $requisition->rqmEndUserid,
                $requisition->rqmReason,
                $requisition->rqmRmks,
                $requisition->rqmSub,
                $requisition->rqmCc,
                $requisition->rqmSite,
                $requisition->rqmEntity,
                $requisition->rqmCurr,
                $requisition->rqmLang,
                $requisition->rqmDirect,
                $requisition->emailOptEntry,
                $requisition->rqmAprvStat,
                $requisition->routeToApr,
                $requisition->routeToBuyer,
                $requisition->allInfoCorrect,
                $requisition->rqdDets->toArray()
            );
        }

        RequisitionMaster::where('rqmNbr', $rqmNbr)->update($updateData);

        // Additional check for specific approval user
        if ($maxExtCostTotal > 5000001 && RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)->where('rqdaAprUserid', 'melvin')->exists()) {
            RequisitionMaster::where('rqmNbr', $rqmNbr)->update([
                'routeToApr' => $requisition->routeToBuyer,
                'rqmAprvStat' => 'Approved',
            ]);

            // Mengirim data ke inboundCreate untuk approval kedua
            $this->inboundCreate(
                $rqmNbr,
                $requisition->enterby,
                $requisition->rqm__log01,
                $requisition->rqmVend,
                $requisition->rqmShip,
                $requisition->rqmReqDate,
                $requisition->rqmNeedDate,
                $requisition->rqmDueDate,
                $requisition->rqmRqbyUserid,
                $requisition->rqmEndUserid,
                $requisition->rqmReason,
                $requisition->rqmRmks,
                $requisition->rqmSub,
                $requisition->rqmCc,
                $requisition->rqmSite,
                $requisition->rqmEntity,
                $requisition->rqmCurr,
                $requisition->rqmLang,
                $requisition->rqmDirect,
                $requisition->emailOptEntry,
                $requisition->rqmAprvStat,
                $requisition->routeToApr,
                $requisition->routeToBuyer,
                $requisition->allInfoCorrect,
                $requisition->rqdDets->toArray()
            );
        }

        Alert::toast('Requisition approved successfully!', 'success');
        return redirect()->route('rqm.browser');
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

    public function declineEmail($rqmNbr, $token)
    {
        // Validate and decrypt token
        try {
            $decrypted = Crypt::decryptString($token);
            [$tokenRqmNbr] = explode('|', $decrypted);

            if ($rqmNbr !== $tokenRqmNbr) {
                return redirect()->route('rqm.browser')->with('error', 'Invalid requisition number.');
            }
            $message = 'Requisition rejected successfully.';
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('rqm.browser')->with('error', 'Invalid or expired token.');
        }

        $currentUser = Auth::user();
        $rqdaTime = rand(10000, 99999);

        // Check if the requisition has already been declined
        if (RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)->where('rqdaAction', 2)->exists()) {
            $message = 'You have already rejected this requisition.';
            return view('page.requisition.notification-approval', compact('message', 'rqmNbr'));
        }

        // Add decline detail
        RequisitionApprovalDetail::create([
            'rqdaNbr' => $rqmNbr,
            'rqdaAction' => 2, // Declined
            'rqdaAprUserid' => $currentUser->username,
            'rqdaTime' => $rqdaTime,
        ]);

        // Update RequisitionMaster
        RequisitionMaster::where('rqmNbr', $rqmNbr)->update([
            'routeToApr' => $currentUser->username,
            'rqmAprvStat' => 'Rejected',
            'rqmStatus' => "c",
            'rqmClsDate' => now(),
        ]);

        // Kirim email ke user yang membuat requisition
        $requisition = RequisitionMaster::where('rqmNbr', $rqmNbr)->first();
        $creator = User::where('username', $requisition->enterby)->first();
        if ($creator) {
            $dataEmail = [
                'rqmNbr' => $requisition->rqmNbr,
                'rqmVend' => $requisition->rqmVend,
                'rqmReqDate' => $requisition->rqmReqDate,
                'rqmNeedDate' => $requisition->rqmNeedDate,
                'rqmDueDate' => $requisition->rqmDueDate,
                'enterby' => $requisition->enterby,
                'rqmEndUserid' => $requisition->rqmEndUserid,
                'rqm__log01' => $requisition->rqm__log01,
                'rqmCc' => $requisition->rqmCc,
                'rqmCurr' => $requisition->rqmCurr,
                'rqmDirect' => $requisition->rqmDirect,
                'rqmAprvStat' => $requisition->rqmAprvStat,
                'rqmRmks' => $requisition->rqmRmks,
                'rqmReason' => $requisition->rqmReason,
                'rqdDets' => $requisition->rqdDets,
            ];

            Mail::to($creator->email)->send(new RequisitionRejected($dataEmail));
        }

        return view('page.requisition.notification-approval', compact('message', 'rqmNbr'));
    }

    public function decline($rqmNbr)
    {
        $currentUser = Auth::user();
        $rqdaTime = rand(10000, 99999);

        // Add decline detail
        RequisitionApprovalDetail::create([
            'rqdaNbr' => $rqmNbr,
            'rqdaAction' => 2, // Declined
            'rqdaAprUserid' => $currentUser->username,
            'rqdaTime' => $rqdaTime,
        ]);

        // Update RequisitionMaster
        RequisitionMaster::where('rqmNbr', $rqmNbr)->update([
            'routeToApr' => $currentUser->username,
            'rqmAprvStat' => 'Rejected',
            'rqmStatus' => "c",
            'rqmClsDate' => now(),
        ]);

        // Kirim email ke user yang membuat requisition
        $requisition = RequisitionMaster::where('rqmNbr', $rqmNbr)->first();
        $creator = User::where('username', $requisition->enterby)->first();
        if ($creator) {
            $dataEmail = [
                'rqmNbr' => $requisition->rqmNbr,
                'rqmVend' => $requisition->rqmVend,
                'rqmReqDate' => $requisition->rqmReqDate,
                'rqmNeedDate' => $requisition->rqmNeedDate,
                'rqmDueDate' => $requisition->rqmDueDate,
                'enterby' => $requisition->enterby,
                'rqmEndUserid' => $requisition->rqmEndUserid,
                'rqm__log01' => $requisition->rqm__log01,
                'rqmCc' => $requisition->rqmCc,
                'rqmCurr' => $requisition->rqmCurr,
                'rqmDirect' => $requisition->rqmDirect,
                'rqmAprvStat' => $requisition->rqmAprvStat,
                'rqmRmks' => $requisition->rqmRmks,
                'rqmReason' => $requisition->rqmReason,
                'rqdDets' => $requisition->rqdDets,
            ];

            Mail::to($creator->email)->send(new RequisitionRejected($dataEmail));
        }

        Alert::toast('Requisition rejected successfully!', 'success');
        return redirect()->route('rqm.browser');
    }

    private function buildCommentParts($commentParts)
    {
        $commentXml = '';

        foreach ($commentParts as $part) {
            if (!empty($part)) {
                $commentXml .= '<cmtCmmt><![CDATA[' . $part . ']]></cmtCmmt>' . "\n";
            }
        }

        return $commentXml;
    }

    private function splitCommentParts($comment)
    {
        return str_split($comment, 75);
    }

    public function wsaNonPO($rqmNbr, $rqm__log01, $enterby)
    {
        try {
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

    public function resend($rqmNbr, $resendTo)
    {
        // \dd($resendTo, $rqmNbr);
        try {
            $requisition = RequisitionMaster::with('rqdDets')->where('rqmNbr', $rqmNbr)->firstOrFail();
            $currentUser = Auth::user();

            // Update RequisitionMaster with new routeToApr
            $requisition->update([
                'routeToApr' => $resendTo,
                'rqmAprvStat' => 'Unapproved',
            ]);

            // Generate unique tokens with rqmNbr
            $approvalToken = Crypt::encryptString($rqmNbr . '|' . $requisition->enterby);
            $declineToken = Crypt::encryptString($rqmNbr . '|' . $requisition->enterby);

            // Send email to the new approver
            $newApprover = User::where('username', $resendTo)->first();
            if ($newApprover) {
                $dataEmail = [
                    'rqmNbr' => $requisition->rqmNbr,
                    'rqmVend' => $requisition->rqmVend,
                    'rqmReqDate' => $requisition->rqmReqDate,
                    'rqmNeedDate' => $requisition->rqmNeedDate,
                    'rqmDueDate' => $requisition->rqmDueDate,
                    'enterby' => $requisition->enterby,
                    'rqmEndUserid' => $requisition->rqmEndUserid,
                    'rqm__log01' => $requisition->rqm__log01,
                    'rqmCc' => $requisition->rqmCc,
                    'rqmCurr' => $requisition->rqmCurr,
                    'rqmDirect' => $requisition->rqmDirect,
                    'rqmAprvStat' => $requisition->rqmAprvStat,
                    'rqmRmks' => $requisition->rqmRmks,
                    'rqmReason' => $requisition->rqmReason,
                    'rqdDets' => $requisition->rqdDets,
                    'approval_link' => route('rqm.approveEmail', ['rqmNbr' => $requisition->rqmNbr, 'token' => $approvalToken]),
                    'decline_link' => route('rqm.declineEmail', ['rqmNbr' => $requisition->rqmNbr, 'token' => $declineToken]),
                ];

                Mail::to($newApprover->email)->send(new PRApproval($dataEmail));
            }

            Alert::toast('Requisition resent successfully!', 'success');
            return redirect()->route('rqm.approval');
        } catch (Exception $e) {
            Log::error('Error resending requisition: ' . $e->getMessage());
            return redirect()->route('rqm.approval')->with('error', 'Failed to resend requisition.');
        }
    }

    public function bulkApproved(Request $request)
    {
        // \dd($request->all());
        $rqmNbrs = $request->input('rqmNbrs');
        $currentUser = Auth::user();

        // Validasi input
        if (!is_array($rqmNbrs) || empty($rqmNbrs)) {
            return response()->json(['error' => 'Tidak ada item yang dipilih atau data tidak valid.'], 400);
        }

        foreach ($rqmNbrs as $rqmNbr) {
            try {
                $requisition = RequisitionMaster::with('rqdDets')->where('rqmNbr', $rqmNbr)->firstOrFail();
                $maxExtCostTotal = $requisition->rqdDets->sum(function ($detail) use ($requisition) {
                    $reqQty = floatval($detail->rqdReqQty);
                    $purCost = floatval($detail->rqdPurCost);
                    $exRate = $requisition->rqmCurr !== 'IDR' ? floatval($requisition->rqmExRate2) : 1;
                    return $reqQty * $purCost * $exRate;
                });

                // Check if the current user has already approved
                if (RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)
                    ->where('rqdaAprUserid', $currentUser->username)
                    ->exists()
                ) {
                    $message = 'You have already approved this requisition.';
                    return view('page.requisition.notification-approval', compact('message', 'rqmNbr'));
                }

                // Perbarui RequisitionMaster berdasarkan kondisi
                if ($maxExtCostTotal > 5000001) {
                    // Tambahkan detail persetujuan
                    RequisitionApprovalDetail::create([
                        'rqdaNbr' => $rqmNbr,
                        'rqdaAction' => 1, // Disetujui
                        'rqdaAprUserid' => $requisition->routeToApr,
                        'rqdaTime' => rand(10000, 99999),
                    ]);

                    $updateData = [
                        'routeToApr' => 'melvin',
                        'rqmAprvStat' => 'Unapproved',
                    ];
                    RequisitionMaster::where('rqmNbr', $rqmNbr)->update($updateData);

                    // Generate unique tokens with rqmNbr
                    $approvalToken = Crypt::encryptString($rqmNbr . '|' . $requisition->enterby);
                    $declineToken = Crypt::encryptString($rqmNbr . '|' . $requisition->enterby);
                    // Send email to user 'melvin' if not already sent
                    if ($requisition->routeToApr !== 'melvin') {
                        $melvinUser = User::where('username', 'melvin')->first();
                        if ($melvinUser && $requisition->rqmAprvStat === 'Unapproved') {
                            $dataEmail = [
                                'rqmNbr' => $requisition->rqmNbr,
                                'rqmVend' => $requisition->rqmVend,
                                'rqmReqDate' => $requisition->rqmReqDate,
                                'rqmNeedDate' => $requisition->rqmNeedDate,
                                'rqmDueDate' => $requisition->rqmDueDate,
                                'enterby' => $requisition->enterby,
                                'rqmEndUserid' => $requisition->rqmEndUserid,
                                'rqm__log01' => $requisition->rqm__log01,
                                'rqmCc' => $requisition->rqmCc,
                                'rqmCurr' => $requisition->rqmCurr,
                                'rqmDirect' => $requisition->rqmDirect,
                                'rqmAprvStat' => $requisition->rqmAprvStat,
                                'rqmRmks' => $requisition->rqmRmks,
                                'rqmReason' => $requisition->rqmReason,
                                'rqdDets' => $requisition->rqdDets,
                                'approval_link' => route('rqm.approveEmail', ['rqmNbr' => $requisition->rqmNbr, 'token' => $approvalToken]),
                                'decline_link' => route('rqm.declineEmail', ['rqmNbr' => $requisition->rqmNbr, 'token' => $declineToken]),
                            ];

                            Mail::to($melvinUser->email)->send(new PRApproval($dataEmail));
                        }
                    }
                } else {
                    // Jika tidak ada maka eksekusi create
                    RequisitionApprovalDetail::create([
                        'rqdaNbr' => $rqmNbr,
                        'rqdaAction' => 1, // Approved
                        'rqdaAprUserid' => $requisition->routeToApr,
                        'rqdaTime' => rand(10000, 99999),
                    ]);

                    // Update RequisitionMaster
                    $updateData = [
                        'routeToApr' => $requisition->routeToBuyer,
                        'rqmAprvStat' => 'Approved',
                    ];

                    RequisitionMaster::where('rqmNbr', $rqmNbr)->update($updateData);

                    // Mengirim data ke inboundCreate
                    $this->inboundCreate(
                        $rqmNbr,
                        $requisition->enterby,
                        $requisition->rqm__log01,
                        $requisition->rqmVend,
                        $requisition->rqmShip,
                        $requisition->rqmReqDate,
                        $requisition->rqmNeedDate,
                        $requisition->rqmDueDate,
                        $requisition->rqmRqbyUserid,
                        $requisition->rqmEndUserid,
                        $requisition->rqmReason,
                        $requisition->rqmRmks,
                        $requisition->rqmSub,
                        $requisition->rqmCc,
                        $requisition->rqmSite,
                        $requisition->rqmEntity,
                        $requisition->rqmCurr,
                        $requisition->rqmLang,
                        $requisition->rqmDirect,
                        $requisition->emailOptEntry,
                        $requisition->rqmAprvStat,
                        $requisition->routeToApr,
                        $requisition->routeToBuyer,
                        $requisition->allInfoCorrect,
                        $requisition->rqdDets->toArray()
                    );
                }

                RequisitionMaster::where('rqmNbr', $rqmNbr)->update($updateData);

                // Additional check for specific approval user
                if ($requisition->routeToApr === 'melvin' && RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)->where('rqdaAprUserid', 'melvin')->exists()) {
                    RequisitionMaster::where('rqmNbr', $rqmNbr)->update([
                        'routeToApr' => $requisition->routeToBuyer,
                        'rqmAprvStat' => 'Approved',
                    ]);

                    // Mengirim data ke inboundCreate untuk approval kedua
                    $this->inboundCreate(
                        $rqmNbr,
                        $requisition->enterby,
                        $requisition->rqm__log01,
                        $requisition->rqmVend,
                        $requisition->rqmShip,
                        $requisition->rqmReqDate,
                        $requisition->rqmNeedDate,
                        $requisition->rqmDueDate,
                        $requisition->rqmRqbyUserid,
                        $requisition->rqmEndUserid,
                        $requisition->rqmReason,
                        $requisition->rqmRmks,
                        $requisition->rqmSub,
                        $requisition->rqmCc,
                        $requisition->rqmSite,
                        $requisition->rqmEntity,
                        $requisition->rqmCurr,
                        $requisition->rqmLang,
                        $requisition->rqmDirect,
                        $requisition->emailOptEntry,
                        $requisition->rqmAprvStat,
                        $requisition->routeToApr,
                        $requisition->routeToBuyer,
                        $requisition->allInfoCorrect,
                        $requisition->rqdDets->toArray()
                    );
                }
            } catch (Exception $e) {
                Log::error('Error approving requisition: ' . $e->getMessage());
            }
        }

        Alert::toast('Requisitions approved successfully!', 'success');
        return redirect()->route('rqm.browser');
    }


    public function inboundCreate($rqmNbr, $enterby, $rqm__log01, $rqmVend, $rqmShip, $rqmReqDate, $rqmNeedDate, $rqmDueDate, $rqmRqbyUserid, $rqmEndUserid, $rqmReason, $rqmRmks, $rqmSub, $rqmCc, $rqmSite, $rqmEntity, $rqmCurr, $rqmLang, $rqmDirect, $emailOptEntry, $rqmAprvStat, $routeToApr, $routeToBuyer, $allInfoCorrect, $items)
    {

        Log::channel('custom')->info('Received request data inbound setelah approval: ' . json_encode(func_get_args()));

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
                        ' . (isset($rqmReason) ? '<rqmReason><![CDATA[' . $rqmReason . ']]></rqmReason>' : '') . '
                        ' . (isset($rqmRmks) ? '<rqmRmks><![CDATA[' . $rqmRmks . ']]></rqmRmks>' : '') . '
                        ' . (isset($rqmSub) ? '<rqmSub>' . $rqmSub . '</rqmSub>' : '') . '
                        <rqmCc>' . $rqmCc . '</rqmCc>
                        <rqmSite>' . $rqmSite . '</rqmSite>
                        <rqmEntity>' . $rqmEntity . '</rqmEntity>
                        <rqmCurr>' . $rqmCurr . '</rqmCurr>
                        <rqmLang>' . $rqmLang . '</rqmLang>
                        <rqmDirect>true</rqmDirect>
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
                            <rqdPart><![CDATA[' . $item['rqdPart'] . ']]></rqdPart>
                            ' . (isset($item['rqdVend']) ? '<rqdVend>' . $item['rqdVend'] . '</rqdVend>' : '') . '
                            <rqdReqQty>' . $item['rqdReqQty'] . '</rqdReqQty>
                            ' . (isset($item['rqdUm']) ? '<rqdUm>' . $item['rqdUm'] . '</rqdUm>' : '') . '
                            <rqdPurCost>' . $item['rqdPurCost'] . '</rqdPurCost>
                            <rqdDiscPct>0</rqdDiscPct>
                            <rqdDueDate>' . $item['rqdDueDate'] . '</rqdDueDate>
                            <rqdNeedDate>' . $item['rqdNeedDate'] . '</rqdNeedDate>
                            <rqdAcct>' . $item['rqdAcct'] . '</rqdAcct>
                            <rqdUmConv>1</rqdUmConv>
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

        Log::channel('custom')->info('Constructed Qdoc request create: ' . $qdocRequest);

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
}
