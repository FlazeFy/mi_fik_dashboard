<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $context;
    public $admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($context, $admin, $body)
    {
        $this->context = $context;
        $this->admin = $admin;
        $this->body = $body;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: '[System] Task Scheduling Information',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('components.email.schedule')
            ->with([
                'context' => $this->context,
                'admin' => $this->admin,
                'body' => $this->body
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
