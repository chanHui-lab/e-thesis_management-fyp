<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule): void
    // {
    //     // $schedule->command('inspire')->hourly();
    // }

    // FOR DEDALINE REMINDER NOTI

    protected $commands = [
        // ... Other commands
        Commands\CheckDeadlines::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:deadlines')->everyMinute(); // Adjust the frequency as needed
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }


}
