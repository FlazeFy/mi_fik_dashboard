<?php

namespace App\Console;

use App\Schedule\HistorySchedule;
use App\Schedule\ContentSchedule;
use App\Schedule\TaskSchedule;
use App\Schedule\UserSchedule;
use App\Schedule\RequestSchedule;
use App\Schedule\TrashSchedule;
use App\Schedule\AccessSchedule;
use App\Schedule\QuestionSchedule;

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
        $schedule->call([new RequestSchedule, 'remind_new_user'])->everyFourHours();
        $schedule->call([new RequestSchedule, 'remind_request'])->everyFourHours();
        $schedule->call([new QuestionSchedule, 'remind_question'])->everySixHours();

        $schedule->call([new ContentSchedule, 'clean'])->dailyAt('01:15');
        $schedule->call([new TrashSchedule, 'clean'])->dailyAt('01:45');
        $schedule->call([new TaskSchedule, 'clean'])->dailyAt('02:30');
        $schedule->call([new HistorySchedule, 'clean'])->dailyAt('03:30');

        $schedule->call([new ContentSchedule, 'reminder'])->hourly();
        $schedule->call([new TaskSchedule, 'reminder'])->hourly();
        
        $schedule->call([new AccessSchedule, 'clean'])->dailyAt('05:15');

        $schedule->call([new UserSchedule, 'clean'])->yearlyOn(1, 2, '23:15');

        // In development
        // $schedule->command(ContentSchedule::clean())->everyMinute();

        // $schedule->command(RequestSchedule::remind_new_user())->everyTwoHours();
        // $schedule->command(RequestSchedule::remind_request())->everyFourHours();
        // $schedule->command(QuestionSchedule::remind_question())->everyFourHours();

        // $schedule->command(ContentSchedule::clean())->dailyAt('01:00');
        // $schedule->command(TrashSchedule::clean())->dailyAt('01:30');
        // $schedule->command(TaskSchedule::clean())->dailyAt('02:30');
        // $schedule->command(HistorySchedule::clean())->dailyAt('03:00');

        // $schedule->command(ContentSchedule::reminder())->hourlyAt(5);
        // $schedule->command(TaskSchedule::reminder())->hourlyAt(15);
        
        // $schedule->command(AccessSchedule::clean())->dailyAt('05:00');

        // $schedule->command(UserSchedule::clean())->yearlyOn(1, 2, '23:00');
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
