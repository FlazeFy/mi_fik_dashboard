<?php

namespace App\Console;

use App\Schedule\HistorySchedule;
use App\Schedule\ContentSchedule;
use App\Schedule\TaskSchedule;
use App\Schedule\UserSchedule;
use App\Schedule\RequestSchedule;

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
        // In staging
        // $schedule->call([new HistorySchedule, 'clean'])->dailyAt('00:40');
        // $schedule->call([new ContentSchedule, 'reminder'])->everyMinute();
        // $schedule->call([new ContentSchedule, 'clean'])->dailyAt('02:00');
        // $schedule->call([new TaskSchedule, 'clean'])->dailyAt('03:00');
        //$schedule->call([new TaskSchedule, 'reminder'])->everyMinute();

        // In development
        // $schedule->command(HistorySchedule::clean())->everyMinute(); 
        // $schedule->command(ContentSchedule::clean())->everyMinute();
        // $schedule->command(ContentSchedule::reminder())->everyMinute();
        // $schedule->command(TaskSchedule::clean())->everyMinute();
        // $schedule->command(UserSchedule::clean())->everyMinute();
        // $schedule->command(TaskSchedule::reminder())->everyMinute();
        $schedule->command(RequestSchedule::remind_request())->everyMinute();
        // $schedule->command(RequestSchedule::remind_new_user())->everyMinute();
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
