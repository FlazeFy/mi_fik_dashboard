<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\PasswordReset;

use App\Mail\ScheduleEmail;
use Illuminate\Support\Facades\Mail;

class PasswordResetSchedule
{
    public static function clean()
    {
        $now = date("Y-m-d H:i:s");
        $schedule = PasswordReset::whereRaw("TIMESTAMPDIFF(MINUTE,created_at,'".$now."') > 30")
            ->whereNull('validated_at')
            ->delete();

        if($schedule > 0){
            $context = "Successfully removed ".$schedule." expired password reset token";
        } else {
            $context = "No data removed from expired password reset token";
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
