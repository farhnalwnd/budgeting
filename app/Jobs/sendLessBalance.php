<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\lessBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class sendLessBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $purchases;
    protected $budgetRequest;
    
    /**
     * Create a new job instance.
     */
    public function __construct($user, $purchases, $budgetRequest)
    {
        $this->user = $user;
        $this->purchases = $purchases;
        $this->budgetRequest = $budgetRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('sendLessBalance Job started');

        $this->budgetRequest->loadMissing(['toDepartment', 'fromDepartment']);

        Log::info('BudgetRequest loaded:', [
            'toDepartment' => optional($this->budgetRequest->toDepartment)->department_name,
            'amount' => $this->budgetRequest->amount,
        ]);

        try {
            Mail::to($this->user->email)->send(new lessBalance(
                $this->user,
                $this->purchases,
                $this->budgetRequest
            ));
            Log::info('Email sent successfully to ' . $this->user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send email:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
