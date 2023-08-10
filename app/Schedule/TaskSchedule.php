<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

use App\Models\History;
use App\Models\Task;
use App\Models\ArchiveRelation;
use App\Models\Admin;
use App\Models\User;
use App\Models\FailedJob;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use App\Helpers\Generator;
use App\Helpers\Query;
use Illuminate\Support\Facades\Mail;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

class TaskSchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DTD_range;
        }

        $contents = Task::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->get();

        foreach($contents as $cts){
            Task::where('id', $cts->id)->delete();
            ArchiveRelation::where('content_id', $cts->id)->delete();
        }

        if(count($contents) > 0){
            $context = "Successfully removed ".count($contents)." task modules with ".$days." days as it days limiter";
        } else {
            $context = "No data removed from task modules with ".$days." days as it days limiter";
        }

        $admin = Admin::all();
        $body = "the system just cleaned some data";
        
        foreach($admin as $ad){
            $username = $ad->username;
            $email = $ad->email;
            
            Mail::to($email)->send(new ScheduleEmail($context, $username, $body));
        }
    }

    public static function reminder()
    {
        try{
            $factory = (new Factory)->withServiceAccount(base_path('/secret/firebase_admin/mifik-83723-firebase-adminsdk-ejmwj-29f65d3ea6.json'));
            $messaging = $factory->createMessaging();
            $select_task = Query::getSelectTemplate("task_schedule");

            $content = Task::selectRaw('task_reminder as content_reminder, '.$select_task.', tasks.created_at, tasks.updated_at, firebase_fcm_token, tasks.created_by as user_id, users.username as created_by')
            // $content = Task::select("tasks.id","task_title","slug_name","task_date_start","task_date_end","task_reminder","tasks.created_by as user_id","users.username as created_by","firebase_fcm_token")
                ->join('users','users.id','=','tasks.created_by')
                ->orderBy('tasks.task_date_start', "DESC")
                ->where('task_reminder','!=','reminder_none')
                ->whereRaw("TIMESTAMPDIFF(HOUR,task_date_start, '".date("Y-m-d H:i:s")."') <= 36") // Give max range based on reminder opt
                ->whereNull('tasks.deleted_at')
                ->get();

            $threeHr = 0;
            $oneHr = 0;
            $threeDay = 0;
            $oneDay = 0;
            
            foreach($content as $ts){
                $now = new DateTime();
                $content_start = new DateTime($ts->content_date_start);
                $diff = $content_start->diff($now);
                $hours = $diff->h;
                $is_remind = false;

                if($hours >= 1 && $hours <= 73){
                    if($ts->content_reminder == "reminder_1_hour_before" && $hours <= 2){
                        $context_start = "1 hour";
                        $is_remind = true;
                        $threeHr++;
                    } else if($ts->content_reminder == "reminder_3_hour_before" && $hours <= 4){
                        $context_start = "3 hour";
                        $is_remind = true;
                        $oneHr++;
                    } else if($ts->content_reminder == "reminder_1_day_before" && $hours <= 25){
                        $context_start = "1 day";
                        $is_remind = true;
                        $oneDay++;
                    } else if($ts->content_reminder == "reminder_3_day_before" && $hours <= 73){
                        $context_start = "3 day";
                        $is_remind = true;
                        $threeDay++;
                    }
                }

                if($is_remind){
                    $firebase_token = $ts->firebase_fcm_token;
                    $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                    if($validateRegister['valid'] != null){
                        // if($hours < 24){
                        //     $notif_body = "The '".$ts->content_title."' is about to start in ".$hours." hours";
                        // } else {
                        //     $days = intval($hours / 24);
                        //     $remainHr = 24 - $hours;
                        //     $notif_body = "The '".$ts->content_title."' is about to start in ".$days." days and ".$remainHr." hours";
                        // }
                        $notif_body = "The '".$ts->content_title."' is about to start in ".$context_start;

                        $notif_title = "Hello ".$ts->created_by.", you got an information";
                        $message = CloudMessage::withTarget('token', $firebase_token)
                            ->withNotification(
                                FireNotif::create($notif_body)
                                ->withTitle($notif_title)
                                ->withBody($notif_body)
                            )
                            ->withData([
                                'slug' => $ts->slug_name,
                                'module' => 'reminder',
                                'type' => 'task',
                                'content_title' => $ts->content_title,
                                'content_date_start' => $ts->content_date_start,
                                'content' => $content
                            ]);
                        $response = $messaging->send($message);

                        Task::where('id',$ts->id)->update([
                            "task_reminder" => "reminder_none"
                        ]);
                    } else {
                        User::where('id', $ts->user_id)->update([
                            "firebase_fcm_token" => null
                        ]);                        
                    }
                }
            }

            $total = $threeHr + $oneHr + $oneDay + $threeDay;
            if($total > 0){
                $context = "Successfully reminded ".$total." tasks and finished ".count($content)." tasks";
            } else if($total == 0 && $content != null){
                $context = "No task has reminded to user. Successfully checked ".count($content)." tasks";
            } else {
                $context = "No task has reminder active";
            }
    
            $admin = Admin::all();
            $body = "the system just checking some task";
            
            foreach($admin as $ad){
                $username = $ad->username;
                $email = $ad->email;
                
                Mail::to($email)->send(new ScheduleEmail($context, $username, $body));
            }
        } catch (\Exception $e) {
            // handle failed job
            $obj = [
                'message' => Generator::getMessageTemplate("custom",'something wrong. Please contact admin',null),
                'stack_trace' => $e->getTraceAsString(), 
                'file' => $e->getFile(), 
                'line' => $e->getLine(), 
            ];
            FailedJob::create([
                'id' => Generator::getUUID(), 
                'type' => "schedule", 
                'status' => "failed",  
                'payload' => json_encode($obj),
                'created_at' => date("Y-m-d H:i:s"), 
                'faced_by' => null
            ]);
        }
    }
}
