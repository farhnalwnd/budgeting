<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\defaultEmail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApprovedPurchaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $admin;
    protected $mailData;
    protected $department;
    /**
     * Create a new job instance.
     */
    public function __construct($admin, $mailData, $department)
    {

        $this->admin=$admin;
        $this->mailData=$mailData;
        $this->department=$department;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->admin->email)->send(new defaultEmail($this->admin, $this->mailData, $this->department));
    }
}
