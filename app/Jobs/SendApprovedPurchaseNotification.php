<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\defaultEmail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendApprovedPurchaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user; 
    protected $data; 
    protected $purchaseDetails;
    protected $isAdmin;
    /**
     * Create a new job instance.
     */
    public function __construct($users, $data, $purchaseDetails, bool $isAdmin)
    {

        $this->user=$users;
        $this->data=$data;
        $this->purchaseDetails=$purchaseDetails;
        $this->isAdmin=$isAdmin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            Log::info('Attempting to send email', [
                'to' => $this->user->email,
                'purchase_id' => $this->data->id ?? null
            ]);

            Mail::to($this->user->email)->send(new DefaultEmail(
                $this->user,
                $this->data,
                $this->purchaseDetails,
                $this->isAdmin
            ));

            Log::info('Email dispatched successfully');
        } catch (\Throwable $e) {
            Log::error('Email sending failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
