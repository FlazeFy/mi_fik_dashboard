<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecoverPassEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $uname;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uname, $token)
    {
        $this->uname = $uname;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Password Recovery',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('components.email.recover_pass')
            ->with([
                'uname' => $this->uname,
                'token' => $this->token,
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
