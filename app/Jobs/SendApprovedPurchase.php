<?php

namespace App\Jobs;

use App\Mail\approvedEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendApprovedPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $purchases;
    protected $budgetRequest;
    protected $deptName;
    protected $purchaseDetails;
    protected $isAdmin;
    /**
     * Create a new job instance.
     */
    public function __construct( $user, $purchases , $budgetRequest, $deptName, $purchaseDetails, bool $isAdmin)
    {
        $this->user=$user;
        $this->purchases=$purchases;
        $this->budgetRequest=$budgetRequest;
        $this->deptName=$deptName;
        $this->purchaseDetails=$purchaseDetails;
        $this->isAdmin=$isAdmin;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new approvedEmail(
            $this->user,
            $this->purchases,
            $this->budgetRequest,
            $this->deptName,
            $this->purchaseDetails,
            $this->isAdmin
        ));
    }
}
