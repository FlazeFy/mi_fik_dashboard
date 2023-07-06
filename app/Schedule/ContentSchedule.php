<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

use App\Models\History;
use App\Models\ContentHeader;
use App\Models\ContentDetail;
use App\Models\ContentViewer;
use App\Models\ArchiveRelation;
use App\Models\Admin;
use App\Models\FailedJob;
use App\Models\User;
use App\Models\SettingSystem;

use App\Mail\ScheduleEmail;
use App\Helpers\Generator;
use App\Helpers\FirebaseTask;
use Illuminate\Support\Facades\Mail;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FireNotif;

class ContentSchedule
{
    public static function clean()
    {
        $setting = SettingSystem::getJobsSetting();
        foreach($setting as $set){
            $days = $set->DCD_range;
        }

        $contents = ContentHeader::whereDate('deleted_at', '<', Carbon::now()->subDays($days))
            ->get();

        foreach($contents as $cts){
            FirebaseTask::deleteContentAttachment($cts->id);

            ContentHeader::where('id', $cts->id)->delete();
            ContentDetail::where('content_id', $cts->id)->delete();

            ContentViewer::where('content_id', $cts->id)->delete();
            ArchiveRelation::where('content_id', $cts->id)->delete();
        }

        if(count($contents) > 0){
            $context = "Successfully removed ".count($contents)." content modules with ".$days." days as it days limiter";
        } else {
            $context = "No data removed from content modules with ".$days." days as it days limiter";
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

            $content = ContentHeader::select("contents_headers.id","content_title","content_tag","content_date_start","content_date_end","content_reminder")
                ->join("contents_details","contents_headers.id","=","contents_details.content_id")
                ->orderBy('contents_headers.content_date_start', "DESC")
                ->where('is_draft', 0)
                ->where('content_reminder','!=','reminder_none')
                ->whereRaw('(DATEDIFF(content_date_start, now()) * -1) < 3') // Give max range based on reminder opt
                ->whereNull('contents_headers.deleted_at')
                ->whereNotNull('content_tag')
                ->get();

            $user = User::all();

            $threeHr = 0;
            $oneHr = 0;
            $threeDay = 0;
            $oneDay = 0;
            $userReminded = 0;
            
            foreach($content as $ct){
                // $now = date("Y-m-d H:i");
                // $content_start = date("Y-m-d H:i", strtotime($ct->content_date_start)); // Check this
                // $diff = $now->diff($content_start);

                $now = new DateTime();
                $content_start = new DateTime($ct->content_date_start);
                $diff = $content_start->diff($now);
                $hours = $diff->h;
                $hours = $hours + ($diff -> days * 24) - 7;
                $is_remind = false;

                if($hours >= 1 && $hours < 73){
                    if($ct->content_reminder == "reminder_3_hour_before" && $hours < 3){
                        $is_remind = true;
                        $threeHr++;
                    } else if($ct->content_reminder == "reminder_1_hour_before" && $hours < 2){
                        $is_remind = true;
                        $oneHr++;
                    } else if($ct->content_reminder == "reminder_1_day_before" && $hours < 24){
                        $is_remind = true;
                        $oneDay++;
                    } else if($ct->content_reminder == "reminder_3_day_before" && $hours < 73){
                        $is_remind = true;
                        $threeDay++;
                    }
                }

                $userLocale = $user;
                if($is_remind && $ct->content_tag != null){
                    $tags = $ct->content_tag;
                    foreach($tags as $tg){
                        if(count($userLocale) != 0){
                            foreach($userLocale as $key => $us){
                                if($us->role && $us->firebase_fcm_token){
                                    $foundInUser = false;
                                    $roles = $us->role;
                                    foreach($roles as $rl){
                                        if($rl['slug_name'] == $tg['slug_name']){
                                            $foundInUser = true;
                                            break;
                                        }
                                    }

                                    if($foundInUser){
                                        $firebase_token = $us->firebase_fcm_token;
                                        $validateRegister = $messaging->validateRegistrationTokens($firebase_token);

                                        if($validateRegister['valid'] != null){
                                            if($hours < 24){
                                                $notif_body = "The '".$ct->content_title."' is about to start in ".$hours." hours";
                                            } else {
                                                $days = intval($hours / 24);
                                                $remainHr = 24 - $hours;
                                                $notif_body = "The '".$ct->content_title."' is about to start in ".$days." days and ".$remainHr." hours";
                                            }

                                            $notif_title = "Hello ".$us->username.", you got an information";
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
                                            $userReminded++;

                                            ContentHeader::where('id',$ct->id)->update([
                                                "content_reminder" => "reminder_none"
                                            ]);
                                        } else {
                                            User::where('id', $us->id)->update([
                                                "firebase_fcm_token" => null
                                            ]);

                                            unset($user[$key]);
                                            
                                        }

                                        unset($userLocale[$key]);
                                    }
                                }
                            }
                        } else {
                            break;
                        }
                    }
                }
            }

            $total = $threeHr + $oneHr + $oneDay + $threeDay;
            if($total > 0){
                $context = "Successfully reminded ".$total." event, about ".$userReminded." users has reminded, and finished checked ".count($content)." events";
            } else if($total == 0 && $content != null){
                $context = "No event has reminded to user. Successfully checked ".count($content)." events";
            } else {
                $context = "No event has reminder active";
            }
    
            $admin = Admin::all();
            $body = "the system just checking some event";
            
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
            FailedJob::create([
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
