<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $send_token;
    public $user;
    public $logos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($send_token, $user, $logos)
    {
        $this->send_token = $send_token;
        $this->user = $user;
        $this->logos = $logos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reset-password');
    }
}
