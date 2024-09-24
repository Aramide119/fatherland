<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Models\PromotePostSubscription;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubscriptionDueReminder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $dueDate;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $subscription;

    public function __construct($user, $dueDate)
    {
        $this->user = $user;
        $this->dueDate = $dueDate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
