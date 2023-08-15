<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrganizerEmail;
use Illuminate\Support\Facades\Mail;

use App\Helpers\Generator;
use App\Models\FailedJob;

class ProcessMailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $header;
    protected $detail;
    protected $receiver;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $detail, $receiver)
    {
        $this->header = $header;
        $this->detail = $detail;
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $email = new OrganizerEmail($this->header, $this->detail);
            Mail::to($this->receiver)->send($email);
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
