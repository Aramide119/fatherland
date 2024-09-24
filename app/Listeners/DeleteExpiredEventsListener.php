<?php

namespace App\Listeners;

use App\Models\Event;
use App\Events\DeleteExpiredEvents;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteExpiredEventsListener
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
     * @param  \App\Events\DeleteExpiredEvents  $event
     * @return void
     */
    public function handle(DeleteExpiredEvents $event)
    {
        Event::where('end_date', '<', now())->delete();
    }
}
