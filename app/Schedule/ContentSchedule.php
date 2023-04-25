<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\History;
use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\ContentViewer;
use App\Models\ArchiveRelation;
use App\Models\Admin;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use Illuminate\Support\Facades\Mail;

class ContentSchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DCD_range;
        }

        $contents = ContentHeader::whereDate('created_at', '<', Carbon::now()->subDays($days))
            ->get();

        foreach($contents as $cts){
            ContentHeader::where('id', $cts->id)->delete();
            ContentDetail::where('content_id', $cts->id)->delete();
            ContentViewer::where('content_id', $cts->id)->delete();
            ArchiveRelation::where('content_id', $cts->id)->delete();
        }

        if($contents > 0){
            $context = "Successfully removed ".count($contents)." content modules with ".$days." days as it days limiter";
        } else {
            $context = "No data removed from content modules with ".$days." days as it days limiter";
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
