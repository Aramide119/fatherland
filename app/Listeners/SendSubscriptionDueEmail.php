<?php

namespace App\Listeners;

use App\Mail\SubscriptionDueEmail;
use Illuminate\Support\Facades\Mail;
use App\Events\SubscriptionDueReminder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSubscriptionDueEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SubscriptionDueReminder  $event
     * @return void
     */
    public function handle(SubscriptionDueReminder $event)
    {
        Mail::to($event->user)->send(new SubscriptionDueEmail($event->user, $event->dueDate));
    }
    
}
