<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PRApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $rqmNbr;
    public $rqmVend;
    public $rqmReqDate;
    public $rqmNeedDate;
    public $rqmDueDate;
    public $enterby;
    public $rqmEndUserid;
    public $rqm__log01;
    public $rqmCc;
    public $rqmCurr;
    public $rqmDirect;
    public $rqmAprvStat;
    public $rqmRmks;
    public $rqmReason;
    public $rqdDets;
    public $approval_link;
    public $decline_link;

    public function __construct($dataEmail)
    {
        $this->rqmNbr = $dataEmail['rqmNbr'];
        $this->rqmVend = $dataEmail['rqmVend'];
        $this->rqmReqDate = $dataEmail['rqmReqDate'];
        $this->rqmNeedDate = $dataEmail['rqmNeedDate'];
        $this->rqmDueDate = $dataEmail['rqmDueDate'];
        $this->enterby = $dataEmail['enterby'];
        $this->rqmEndUserid = $dataEmail['rqmEndUserid'];
        $this->rqm__log01 = $dataEmail['rqm__log01'];
        $this->rqmCc = $dataEmail['rqmCc'];
        $this->rqmCurr = $dataEmail['rqmCurr'];
        $this->rqmDirect = $dataEmail['rqmDirect'];
        $this->rqmAprvStat = $dataEmail['rqmAprvStat'];
        $this->rqmRmks = $dataEmail['rqmRmks'];
        $this->rqmReason = $dataEmail['rqmReason'];
        $this->rqdDets = $dataEmail['rqdDets'];
        $this->approval_link = $dataEmail['approval_link'];
        $this->decline_link = $dataEmail['decline_link'];
    }

    public function build()
    {
        return $this->view('emails.PRApproval')
            ->subject('New Purchase Request Created')
            // ->attach(public_path('assets/images/logo/logo.png'), [
            //     'as' => 'logo.png',
            //     'mime' => 'image/png',
            //     'content_id' => 'logo.png', // Ensure this matches the CID in the email view
            // ])
            ->with([
                'rqmNbr' => $this->rqmNbr,
                'rqmVend' => $this->rqmVend,
                'rqmReqDate' => $this->rqmReqDate,
                'rqmNeedDate' => $this->rqmNeedDate,
                'rqmDueDate' => $this->rqmDueDate,
                'enterby' => $this->enterby,
                'rqmEndUserid' => $this->rqmEndUserid,
                'rqm__log01' => $this->rqm__log01,
                'rqmCc' => $this->rqmCc,
                'rqmCurr' => $this->rqmCurr,
                'rqmDirect' => $this->rqmDirect,
                'rqmAprvStat' => $this->rqmAprvStat,
                'rqmRmks' => $this->rqmRmks,
                'rqmReason' => $this->rqmReason,
                'rqdDets' => $this->rqdDets,
                'approval_link' => $this->approval_link,
                'decline_link' => $this->decline_link,
            ]);
    }
}
