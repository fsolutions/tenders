<?php

namespace App\Console;

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
        // $schedule->command('orders:sendmailstotelegram')->cron('*/3 * * * *');
        $schedule->command('orders:sendneworderstotelegram')->everyMinute()->withoutOverlapping(10);
        $schedule->command('orders:sendnewtotelegram')->everyTenMinutes()->withoutOverlapping(10);
        $schedule->command('orders:sendnewtowhatsup')->everyTenMinutes()->withoutOverlapping(10);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
