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
use App\Models\Job;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use App\Helpers\Generator;
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

        $contents = Task::whereDate('created_at', '<', Carbon::now()->subDays($days))
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

            $content = Task::select("tasks.id","task_title","task_date_start","task_date_end","task_reminder","tasks.created_by as user_id","users.username as created_by","firebase_fcm_token")
                ->join('users','users.id','=','tasks.created_by')
                ->orderBy('tasks.task_date_start', "DESC")
                ->where('task_reminder','!=','reminder_none')
                ->whereRaw('(DATEDIFF(task_date_start, now()) * -1) < 3') // Give max range based on reminder opt
                ->whereNull('tasks.deleted_at')
                ->get();

            $threeHr = 0;
            $oneHr = 0;
            $threeDay = 0;
            $oneDay = 0;
            
            foreach($content as $ts){
                $now = new DateTime();
                $content_start = new DateTime($ts->task_date_start);
                $diff = $content_start->diff($now);
                $hours = $diff->h;
                $hours = $hours + ($diff -> days * 24) - 7;
                $is_remind = false;

                if($hours >= 1 && $hours < 73){
                    if($ts->task_reminder == "reminder_3_hour_before" && $hours < 3){
                        $is_remind = true;
                        $threeHr++;
                    } else if($ts->task_reminder == "reminder_1_hour_before" && $hours < 2){
                        $is_remind = true;
                        $oneHr++;
                    } else if($ts->task_reminder == "reminder_1_day_before" && $hours < 24){
                        $is_remind = true;
                        $oneDay++;
                    } else if($ts->task_reminder == "reminder_3_day_before" && $hours < 73){
                        $is_remind = true;
                        $threeDay++;
                    }
                }

                if($is_remind){
                    $firebase_token = $ts->firebase_fcm_token;
                    $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                    if($validateRegister['valid'] != null){
                        if($hours < 24){
                            $notif_body = "The '".$ts->task_title."' is about to start in ".$hours." hours";
                        } else {
                            $days = intval($hours / 24);
                            $remainHr = 24 - $hours;
                            $notif_body = "The '".$ts->task_title."' is about to start in ".$days." days and ".$remainHr." hours";
                        }

                        $notif_title = "Hello ".$ts->created_by.", you got an information";
                        $message = CloudMessage::withTarget('token', $firebase_token)
                            ->withNotification(
                                FireNotif::create($notif_body)
                                ->withTitle($notif_title)
                                ->withBody($notif_body)
                            )
                            ->withData([
                                'by' => 'person'
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
                'message' => $e->getMessage(), 
                'stack_trace' => $e->getTraceAsString(), 
                'file' => $e->getFile(), 
                'line' => $e->getLine(), 
            ];
            Job::create([
                'id' => Generator::getUUID(), 
                'type' => "schedule", 
                'status' => "failed",  
                'payload' => json_encode($obj),
                'created_at' => date("Y-m-d H:i:s"), 
                'faced_by' => null, 
                'fixed_at' => null
            ]);
        }
    }
}
