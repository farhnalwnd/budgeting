<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\requestApproval;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class sendApprovalRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $approver;
    protected $requestData;
    protected $budgetApproval;
    /**
     * Create a new job instance.
     */
    public function __construct($approver, $requestData, $budgetApproval)
    {
        $this->approver=$approver;
        $this->requestData=$requestData;
        $this->budgetApproval=$budgetApproval;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $approveLink = route('budgeting.request.approved', [
            'budget_req_no'=>$this->budgetApproval->budget_req_no,
            'nik'=>$this->budgetApproval->nik,
            'token'=>$this->budgetApproval->token,
            'status'=>'approve',
        ]);
        $rejectLink = route('budgeting.request.reject', [
            'budget_req_no'=>$this->budgetApproval->budget_req_no,
            'nik'=>$this->budgetApproval->nik,
            'status'=> 'reject',
            'token'=>$this->budgetApproval->token,
        ]);
        // dd('email s', $this->requestData);
        if($this->approver->email){
            Mail::to($this->approver->email)->send(new requestApproval($this->requestData, $this->approver, $this->budgetApproval, $approveLink, $rejectLink));
        }else{
            Log::error('Approver email is missing for NIK: ' . $this->approver->nik);
        }
    }
}
