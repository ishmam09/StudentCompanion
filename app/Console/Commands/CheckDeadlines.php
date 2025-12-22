<?php

namespace App\Console\Commands;

use App\Models\Deadline;
use App\Notifications\UpcomingDeadlineNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadlines:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for upcoming deadlines and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $upcomingDeadlines = Deadline::where('status', 'pending')
            ->where('reminder_sent', false)
            ->where('due_date', '<=', Carbon::now()->addHours(24))
            ->get();

        foreach ($upcomingDeadlines as $deadline) {
            $deadline->user->notify(new UpcomingDeadlineNotification($deadline));
            $deadline->update(['reminder_sent' => true]);
            $this->info("Notification sent for: {$deadline->title}");
        }

        $this->info('Deadlines checked successfully.');
    }
}
