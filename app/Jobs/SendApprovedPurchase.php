<?php

namespace App\Jobs;

use App\Mail\approvedEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApprovedPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $admin;
    protected $mailData;
    /**
     * Create a new job instance.
     */
    public function __construct($admin, $mailData)
    {
        $this->admin=$admin;
        $this->mailData=$mailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->admin->email)->send(new approvedEmail($this->admin, $this->mailData));
    }
}
