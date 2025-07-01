<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class rejectStatus extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $purchases;
    protected $budgetRequest;
    protected $deptName;
    protected $purchaseDetails;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $purchases , $budgetRequest, $deptName, $purchaseDetails)
    {
        $this->user=$user;
        $this->purchases=$purchases;
        $this->budgetRequest=$budgetRequest;
        $this->deptName=$deptName;
        $this->purchaseDetails=$purchaseDetails;
    }

    public function build()
{
    Log::info($this->budgetRequest->budget_req_no);
    return $this->subject("peminjaman dana direject oleh")
    ->markdown('emails.userRejected')
    ->with([
        'user'=> $this->user,
        'purchases'=> $this->purchases,
        'budgetRequest'=> $this->budgetRequest,
        'deptName'=> $this->deptName,
        'purchaseDetails'=>$this->purchaseDetails
    ]);
}

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
