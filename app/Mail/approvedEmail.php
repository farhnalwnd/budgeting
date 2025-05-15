<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class approvedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $admin;
    protected $mailData;
    protected $budgetRequest;
    protected $purchase;
    /**
     * Create a new message instance.
     */
    public function __construct($admin, $mailData, $budgetRequest, $purchase)
    {
        $this->admin= $admin;
        $this->mailData=$mailData;
        $this->budgetRequest=$budgetRequest;
        $this->purchase=$purchase;
    }

    public function build(){
        return $this->subject("list budgeting approved")
        ->markdown('emails.requestApproved')
        ->with([
            'admin'=> $this->admin,
            'mailData'=>$this->mailData,
            'budgetRequest'=>$this->budgetRequest,
            'purchase'=>$this->purchase
            ]);

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Defaul Email',
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
