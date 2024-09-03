<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequisitionRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $dataEmail;

    /**
     * Create a new message instance.
     *
     * @param array $dataEmail
     * @return void
     */
    public function __construct(array $dataEmail)
    {
        $this->dataEmail = $dataEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Requisition Rejected')
                    ->view('emails.requisition_rejected')
                    ->with('dataEmail', $this->dataEmail);
    }
}
