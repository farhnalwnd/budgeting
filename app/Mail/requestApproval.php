<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class requestApproval extends Mailable
{
    use Queueable, SerializesModels;

    protected $requestData;
    protected $approver;
    protected $budgetApproval;
    protected $approveLink;
    protected $rejectLink;
    /**
     * Create a new message instance.
     */
    public function __construct($requestData, $approver, $budgetApproval, $approveLink, $rejectLink)
    {
        $this->requestData = $requestData;
        $this->approver = $approver;
        $this->budgetApproval = $budgetApproval;
        $this->approveLink = $approveLink;
        $this->rejectLink = $rejectLink;
    }

    public function build(){
        return $this->subject("Permohonan peminjaman dana untuk keperluan divisi {$this->requestData['from_department_name']}")
        ->markdown('emails.requestApprover')
        ->with([
            'requestData'=> $this->requestData,
            'approver'=> $this->approver,
            'approveLink'=> $this->approveLink,
            'rejectLink'=> $this->rejectLink
            ]);

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Approval',
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
