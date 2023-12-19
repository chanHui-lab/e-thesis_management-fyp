<?php

namespace App\Console\Commands;

use App\Models\SubmissionPost;
use App\Models\User;

use Carbon\Carbon;

use App\Events\DeadlineApproachingEvent; // Add or confirm this use statement
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog; // Import the Log facade with an alias

class CheckDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected $signature = 'check:deadlines';
    protected $description = 'Check submission deadlines and send notifications';

    public function handle()
    {
        try {
            LaravelLog::info('CheckDeadlines command executed successfully.');

            // Check if a user is logged in

            // Manually log in a user for testing purposes
            // $user = \App\Models\User::find(5); // Adjust the user ID as needed
            // Auth::login($user);
            $user = User::find(5); // Replace $userId with the actual user ID
            Auth::login($user);
            // $user = Auth::user();
            if ($user) {
                // Your logic here
                $upcomingDeadlines = SubmissionPost::where('submission_deadline', '>', now())
                ->where('submission_deadline', '<=', now()->addWeeks(2))
                ->get();

                foreach ($upcomingDeadlines as $submission) {
                    \Illuminate\Support\Facades\Log::info("Dispatching DeadlineApproachingEvent for submission {$submission->id}");

                    // Notify the user about the upcoming deadline
                    // $submission->user->notify(new DeadlineReminderNotification($submission));
                    event(new DeadlineApproachingEvent($submission));
                }

                $this->info('Deadline check completed.');
                // Example: Output the user's name
                $this->info('Currently logged-in user: ' . $user->email);
            } else {
                $this->info('No user is currently logged in.');
            }


        }catch(\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Error during deadline check: ' . $e->getMessage());
             $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}
