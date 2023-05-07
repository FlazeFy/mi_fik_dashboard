<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\History;
use App\Models\Task;
use App\Models\ArchiveRelation;
use App\Models\Admin;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use Illuminate\Support\Facades\Mail;

class TaskSchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DTD_range;
        }

        $contents = Task::whereDate('created_at', '<', Carbon::now()->subDays($days))
            ->get();

        foreach($contents as $cts){
            Task::where('id', $cts->id)->delete();
            ArchiveRelation::where('content_id', $cts->id)->delete();
        }

        if($contents > 0){
            $context = "Successfully removed ".count($contents)." task modules with ".$days." days as it days limiter";
        } else {
            $context = "No data removed from task modules with ".$days." days as it days limiter";
        }

        // Fix the mail problem on staging first
        $admin = Admin::all();

        foreach($admin as $ad){
            $username = $ad->username;
            $email = $ad->email;
            
            Mail::to($email)->send(new ScheduleEmail($context, $username));
        }
    }
}
