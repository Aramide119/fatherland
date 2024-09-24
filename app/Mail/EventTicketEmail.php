<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventTicketEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $event;
    public $ticketType;
    public $ticketId;
    public $quantity;
    public $total;
    public $logos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $event, $ticketType, $ticketId, $quantity, $total, $logos)
    {
        $this->user = $user;
        $this->event = $event;
        $this->ticketType =$ticketType;
        $this->ticketId=$ticketId;
        $this->quantity=$quantity;
        $this->total=$total;
        $this->logos=$logos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.event_ticket');
    }
}
