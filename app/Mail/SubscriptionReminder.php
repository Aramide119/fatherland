<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $daysLeft;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $daysLeft)
    {
        $this->user = $user;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Subscription Reminder')->view('emails.subscription_reminder');
    }
}
