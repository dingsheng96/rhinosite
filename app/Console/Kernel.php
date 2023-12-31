<?php

namespace App\Console;

use App\Tasks\FailOverdueTransaction;
use Illuminate\Console\Scheduling\Schedule;
use App\Tasks\DeactivateExpiredSubscription;
use App\Tasks\SendSubscriptionPreExpireNotification;
use App\Tasks\SendSubscriptionPostExpireNotification;
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
        $schedule->call(new DeactivateExpiredSubscription)->daily();
        $schedule->call(new FailOverdueTransaction)->daily();
        $schedule->call(new SendSubscriptionPreExpireNotification)->daily();
        $schedule->call(new SendSubscriptionPostExpireNotification)->daily();
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
