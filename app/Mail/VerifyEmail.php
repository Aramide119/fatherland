<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $logos;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $logos)
    {
        $this->user = $user;
        $this->logos = $logos;
  
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verify-email');
    }
}
