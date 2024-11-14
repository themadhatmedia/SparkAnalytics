<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoDailyAletr;
use App\Console\Commands\AutoWeeklyAlert;
use App\Console\Commands\AutoMonthlyAlert;
use App\Console\Commands\AutoDailyReport;
use App\Console\Commands\AutoMonthlyReport;
use App\Console\Commands\AutoweeklyReport;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        'App\Console\Commands\AutoDailyAletr',
        'App\Console\Commands\AutoWeeklyAlert',
        'App\Console\Commands\AutoMonthlyAlert',
        'App\Console\Commands\AutoDailyReport',
        'App\Console\Commands\AutoweeklyReport',
        'App\Console\Commands\AutoMonthlyReport',
    ];
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('auto:dailyaletr')->daily();
         $schedule->command('auto:weeklyaletr')->weekly();
         $schedule->command('auto:monthlyaletr')->monthly();
         $schedule->command('auto:dailyreport')->daily();
         $schedule->command('auto:weeklyreport')->weekly();
         $schedule->command('auto:monthlyreport')->monthly();
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
