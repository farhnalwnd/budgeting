<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class defaultEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $admin;
    protected $mailData;
    protected $department;
    /**
     * Create a new message instance.
     */
    public function __construct($admin, $mailData, $department)
    {
        $this->admin= $admin;
        $this->mailData=$mailData;
        $this->department=$department;
    }

    public function build(){
        return $this->subject("list budgeting approved")
        ->markdown('emails.editApproved')
        ->with([
            'admin'=> $this->admin,
            'mailData'=>$this->mailData,
            'department'=>$this->department
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
