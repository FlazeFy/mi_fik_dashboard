<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

use App\Models\Tag;
use App\Models\UserGroup;
use App\Models\Admin;
use App\Models\FailedJob;
use App\Models\SettingSystem;
use App\Models\Info;
use App\Models\Feedback;
use App\Models\Question;
use App\Models\Dictionary;
use App\Models\GroupRelation;

use App\Mail\ScheduleEmail;
use App\Helpers\Generator;
use App\Helpers\Converter;
use Illuminate\Support\Facades\Mail;

class TrashSchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DCD_range;
        }

        $tag = Tag::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $group = UserGroup::select('id')->whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->get();
        foreach($group as $gr){
            GroupRelation::where('group_id', $gr->id)->delete();
            UserGroup::destroy($gr->id);
        }
        
        $info = Info::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $feedback = Feedback::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $question = Question::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->delete();
            
        $dictionary = Dictionary::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $totalgroup = count($group);
        $total = $tag + $totalgroup + $info + $feedback + $question + $dictionary;

        if($total > 0){
            $context = "Successfully cleaned ".$total." data in trash modules with ".$days." days as it days limiter.".
                " About ".Converter::getMsgTrashPerContext($tag, "tag")."".Converter::getMsgTrashPerContext($totalgroup, "group")."".Converter::getMsgTrashPerContext($info, "info").
                "".Converter::getMsgTrashPerContext($feedback, "feedback")."".Converter::getMsgTrashPerContext($question, "question")."".Converter::getMsgTrashPerContext($dictionary, "dictionary");
        } else {
            $context = "No data cleaned from trash modules with ".$days." days as it days limiter";
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
