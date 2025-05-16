<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reject Status',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
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
