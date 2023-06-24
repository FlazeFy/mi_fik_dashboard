<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\History;
use App\Models\Admin;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use Illuminate\Support\Facades\Mail;

class HistorySchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DHD_range;
        }

        $schedule = History::whereDate('created_at', '<', Carbon::now()->subDays($days))
            ->delete();

        if($schedule > 0){
            $context = "Successfully removed ".$schedule." history with ".$days." days as it days limiter";
        } else {
            $context = "No data removed from history with ".$days." days as it days limiter";
        }

        // Fix the mail problem on staging first
        $admin = Admin::all();
        $body = "the system just cleaned some data";
        
        foreach($admin as $ad){
            $username = $ad->username;
            $email = $ad->email;
            
            Mail::to($email)->send(new ScheduleEmail($context, $username, $body));
        }
    }
}
