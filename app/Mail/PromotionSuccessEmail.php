<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PromotionSuccessEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $amountPaid;
    public $daysDue;
   public  $duration;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($amountPaid, $daysDue, $user, $duration)
    { 
        $this->user = $user;
        $this->amountPaid = $amountPaid;
        $this->daysDue = $daysDue;
        $this->duration = $duration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.promotion_success');
        // ->with([
        //     "userName" => $this->user,
        //     'amountPaid' => $this->amountPaid,
        //     'daysDue' => $this->daysDue,
        // ]);

    }
}
