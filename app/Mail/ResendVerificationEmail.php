<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResendVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public User $user, public $token, public $logos)
    {
        
    }

    
    public function build()
    {
        return $this->view('emails.resend_verification')
                    ->with([
                        'user' => $this->user,
                        'token' => $this->token,
                        'logos'=>$this->logos,
                    ])
                    ->subject('Resend Verification Email');
    }
}
