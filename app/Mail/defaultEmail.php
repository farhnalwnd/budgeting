<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
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
    
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $data = is_array($this->data) && isset($this->data['App\\Models\\Budgeting\\Purchase'])
            ? $this->data['App\\Models\\Budgeting\\Purchase']
            : $this->data;

        if (is_array($data)) {
            $data = (object)$data;
        }

        Log::info('Processed data for view:', ['data' => $data]);

        $subject = $this->isAdmin
            ? 'Notifikasi data baru yang memiliki status approved'
            : 'Purchases anda sudah berstatus approved';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $data = is_array($this->data) && isset($this->data['App\\Models\\Budgeting\\Purchase'])
            ? $this->data['App\\Models\\Budgeting\\Purchase']
            : $this->data;

        if (is_array($data)) {
            $data = (object)$data;
        }

        return new Content(
            markdown: 'emails.approved',
            with: [
                'user' => $this->user,
                'data' => $data,
                'purchaseDetails' => $this->purchaseDetails,
                'isAdmin' => $this->isAdmin,
            ],
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
