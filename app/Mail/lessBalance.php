<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class lessBalance extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $purchases;
    protected $budgetRequest;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $purchases, $budgetRequest)
    {
        $this->user = $user;
        $this->purchases = $purchases;
        $this->budgetRequest = $budgetRequest;
    }

    public function build(){
        Log::info('Building lessBalance email for: ' . $this->user->email);
        Log::info('Purchases:', ['no' => $this->purchases->purchase_no]);
        Log::info('BudgetRequest:', [
            'amount' => $this->budgetRequest->amount,
            'toDepartment' => optional($this->budgetRequest->toDepartment)->department_name,
        ]);

        return $this->markdown('emails.failedPurchase')->with([
            'user' => $this->user,
            'purchases' => $this->purchases,
            'budgetRequest' => $this->budgetRequest,
        ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'request budget dengan nomor purchase ' . $this->purchases->purchase_no . ' berhasil tetapi purchase rejected'
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
