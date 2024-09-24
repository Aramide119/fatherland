<?php

namespace App\Console;

use App\Events\SubscriptionDueReminder;
use App\Events\DeleteExpiredEvents;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send:subscription-reminders')->dailyAt('09:00');
        $schedule->command('subscription:send-reminder')->daily();
        // $schedule->command('events:delete-expired')->daily('08:40');
        $schedule->call(function () {
            event(new DeleteExpiredEvents());
        })->daily();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
