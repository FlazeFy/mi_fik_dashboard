<?php

namespace App\Schedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

use App\Models\Question;
use App\Models\Admin;
use App\Models\FailedJob;

use App\Mail\QuestionEmail;
use App\Helpers\Generator;
use Illuminate\Support\Facades\Mail;

class QuestionSchedule
{
    public static function remind_question()
    {
        try{
            $que = Question::select("question_type","question_body","username")
                ->join("users","users.id","=","questions.created_by")
                ->whereNull('question_answer')
                ->whereNull('questions.deleted_at')
                ->whereNull('questions.updated_at')
                ->get();

            if(count($que) > 0){
                $context = "You have ".count($que)." awating question to respond";
            } else {
                $context = "You have no question waiting to respond";
            }

            $admin = Admin::all();
            $body = "the system just checked some question";

            foreach($admin as $ad){
                $username = $ad->username;
                $email = $ad->email;
                
                Mail::to($email)->send(new QuestionEmail($context, $username, $body, $que));
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
