<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

use App\Models\History;
use App\Models\ContentHeader;
use App\Models\ContentViewer;
use App\Models\ArchiveRelation;
use App\Models\Admin;
use App\Models\Job;
use App\Models\User;
use App\Models\Task;
use App\Models\Archive;
use App\Models\GroupRelation;

use App\Mail\ScheduleEmail;
use App\Helpers\Generator;
use Illuminate\Support\Facades\Mail;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

class UserSchedule
{
    public static function clean()
    {
        $user = User::where('valid_until', '<', date("Y"))
            ->whereNotNull('valid_until')
            ->get();

        foreach($user as $us){
            ContentHeader::where('created_by', $us->id)->delete();
            Task::where('created_by', $us->id)->delete();
            ContentViewer::where('created_by', $us->id)->delete();
            Archive::where('created_by', $us->id)->delete();
            ArchiveRelation::where('created_by', $us->id)->delete();
            GroupRelation::where('user_id', $us->id)->delete();
            History::where('created_by', $us->id)
                ->orWhere('context_id', $us->id)
                ->orWhere('history_send_to', $us->id)
                ->delete();
            User::where('id', $us->id)->delete();
        }

        if(count($user) > 0){
            $context = "Successfully removed ".count($user)." user, who has passed the valid until year";
        } else {
            $context = "No data removed from user modules";
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
