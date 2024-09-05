<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendReminderEmail;
use App\Models\DOC\Reminder;
use Carbon\Carbon;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails based on reminder settings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reminders = Reminder::with('document.user')->get();

        foreach ($reminders as $reminder) {
            $expiryDate = Carbon::parse($reminder->document->expiry_date);
            $reminderDate = $expiryDate->subMonths($reminder->reminder_exp_date);

            if (Carbon::now()->greaterThanOrEqualTo($reminderDate)) {
                $daysSinceReminder = Carbon::now()->diffInDays($reminderDate);
                if ($daysSinceReminder % $reminder->reminder_interval == 0) {
                    SendReminderEmail::dispatch($reminder);
                }
            }
        }

        return 0;
    }
}
