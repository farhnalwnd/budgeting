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

    protected $user;
    protected $data;
    protected $purchaseDetails;
    protected $isAdmin;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $data, $purchaseDetails, bool $isAdmin)
    {
        $this->user=$user;
        $this->data=$data;
        $this->purchaseDetails=$purchaseDetails;
        $this->isAdmin=$isAdmin;
    }

    public function build(){
        $subject = $this->isAdmin ? 'notifikasi data baru yang memiliki status approved':'purchases anda sudah berstatus approved';
        return $this->subject($subject)
        ->markdown('emails.approved')
        ->with([
            'user'=>$this->user,
            'data'=> $this->data,
            'purchaseDetails' => $this->purchaseDetails,
            'isAdmin' => $this->isAdmin,
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isAdmin ? 'notifikasi data baru yang memiliki status approved':'purchases anda sudah berstatus approved';
        return new Envelope(
            subject: $subject
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
