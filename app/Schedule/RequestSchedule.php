<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

use App\Models\UserRequest;
use App\Models\Admin;
use App\Models\FailedJob;
use App\Models\User;

use App\Mail\NewUserEmail;
use App\Mail\RequestEmail;
use App\Helpers\Generator;
use Illuminate\Support\Facades\Mail;

class RequestSchedule
{
    public static function remind_request()
    {
        try{
            $req = UserRequest::select("tag_slug_name","request_type","username","first_name", "last_name")
                ->join("users","users.id","=","users_requests.created_by")
                ->whereNull('users_requests.accepted_at')
                ->whereNull('rejected_at')
                ->get();

            if(count($req) > 0){
                $context = "You have ".count($req)." awating request to respond";
            } else {
                $context = "You have no request waiting to respond";
            }

            $admin = Admin::all();
            $body = "the system just checked some request";

            foreach($admin as $ad){
                $username = $ad->username;
                $email = $ad->email;
                
                Mail::to($email)->send(new RequestEmail($context, $username, $body, $req));
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

    public static function remind_new_user()
    {
        try{
            $req = User::select("username", "first_name", "last_name", "role", "accepted_at")
                ->whereNull('role')
                ->orWhereNull('accepted_at')
                ->get();

            if(count($req) > 0){
                $context = "You have ".count($req)." user who doesn't have a general role or their account doesn't accepted. So they can't explore all Mi-FIK feature";
            } else {
                $context = "All of user already have a role and accepted";
            }

            $admin = Admin::all();
            $body = "the system just checked some user";

            foreach($admin as $ad){
                $username = $ad->username;
                $email = $ad->email;
                
                Mail::to($email)->send(new NewUserEmail($context, $username, $body, $req));
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
