<?php

namespace App\Http\Controllers\QAD;

use App\Http\Controllers\Controller;
use App\Models\QAD\RequisitionApprovalDetail;
use Illuminate\Http\Request;

class RequisitionApprovalDetailController extends Controller
{
    public function getApprovalStatus(Request $request)
    {
        $rqmNbr = $request->input('rqmNbr');

        // Query the RequisitionApprovalDetail table to get the approval status
        $approvalDetails = RequisitionApprovalDetail::where('rqdaNbr', $rqmNbr)
            ->get()
            ->pluck('rqdaAction')
            ->all();

        $approvalsGiven = count(array_filter($approvalDetails, function ($action) {
            return $action === "1";
        }));

        $approvalsTotal = count($approvalDetails);

        return response()->json([
            'rqmNbr' => $rqmNbr, // Added rqmNbr to the response
            'approvalsGiven' => $approvalsGiven,
            'approvalsTotal' => $approvalsTotal,
        ]);
    }

    public function getAllApprovalStatuses()
    {
        // Ambil semua data dari tabel RequisitionApprovalDetail
        $approvalDetails = RequisitionApprovalDetail::all();

        // Kelompokkan data berdasarkan rqdaNbr
        $groupedDetails = $approvalDetails->groupBy('rqdaNbr');

        $response = $groupedDetails->map(function ($details, $rqmNbr) {
            if (empty($rqmNbr)) {
                return null; // Abaikan entri dengan rqmNbr kosong
            }

            $approvalsGiven = count(array_filter($details->pluck('rqdaAction')->all(), function ($action) {
                return $action === "1";
            }));

            $approvalsTotal = count($details);

            return [
                'rqmNbr' => $rqmNbr,
                'approvalsGiven' => $approvalsGiven,
                'approvalsTotal' => $approvalsTotal,
            ];
        })->filter(); // Hapus entri null dari koleksi

        return response()->json($response->values());
    }
}
