<?php

namespace App\Jobs;

use App\Mail\rejectStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendRejectedPurchaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $purchases;
    protected $budgetRequest;
    protected $deptName;
    protected $purchaseDetails;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $purchases , $budgetRequest, $deptName, $purchaseDetails)
    {
        $this->user=$user;
        $this->purchases=$purchases;
        $this->budgetRequest=$budgetRequest;
        $this->deptName=$deptName;
        $this->purchaseDetails=$purchaseDetails;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new rejectStatus(
            $this->user, 
            $this->purchases, 
            $this->budgetRequest, 
            $this->deptName,
            $this->purchaseDetails
        ));
    }
}
