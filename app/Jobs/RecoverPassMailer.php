<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\RecoverPassEmail;
use Illuminate\Support\Facades\Mail;

use App\Helpers\Generator;
use App\Models\FailedJob;

class RecoverPassMailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uname;
    protected $receiver;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uname, $receiver, $token)
    {
        $this->uname = $uname;
        $this->receiver = $receiver;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $email = new RecoverPassEmail($this->uname, $this->token);
            Mail::to($this->receiver)->send($email);
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
                'faced_by' => null
            ]);
        }
    }
}
