<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PersonalAccessTokens;
use App\Models\Admin;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use Illuminate\Support\Facades\Mail;

class AccessSchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DCD_range;
        }

        $schedule = PersonalAccessTokens::whereDate('last_used_at', '<', Carbon::now()->subDays($days))
            ->delete();

        if($schedule > 0){
            $context = "Successfully removed ".$schedule." access token with ".$days." days as it days limiter";
        } else {
            $context = "No data removed from access token with ".$days." days as it days limiter";
        }

        $admin = Admin::all();
        $body = "the system just cleaned some data";
        
        foreach($admin as $ad){
            $username = $ad->username;
            $email = $ad->email;
            
            Mail::to($email)->send(new ScheduleEmail($context, $username, $body));
        }
    }
}
