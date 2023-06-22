<?php

namespace App\Console;

use App\Schedule\HistorySchedule;
use App\Schedule\ContentSchedule;
use App\Schedule\TaskSchedule;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\ScheduleMonitor\ScheduleHealth;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // In production

        
        // In staging
        // $schedule->call([new HistorySchedule, 'clean'])->dailyAt('00:40');
        // $schedule->call([new ContentSchedule, 'reminder'])->everyMinute();
        // $schedule->call([new ContentSchedule, 'clean'])->dailyAt('02:00');
        // $schedule->call([new TaskSchedule, 'clean'])->dailyAt('03:00');

        // In development
        // $schedule->command(HistorySchedule::clean())->everyMinute(); // Check this shit
        // $schedule->command(ContentSchedule::clean())->everyMinute();
        $schedule->command(ContentSchedule::reminder())->everyMinute();
        // $schedule->command(TaskSchedule::clean())->everyMinute();
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
