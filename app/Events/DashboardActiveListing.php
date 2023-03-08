<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardActiveListing implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $actionId;
    public $actionData;
    public $user;


    /**
     * Create a new event instance.
     *
     * @author Author
     *
     * @return void
     */
    public function __construct($actionId, $actionData, $user)
    {
        $this->actionId = $actionId;
        $this->actionData = $actionData;
        $this->user = $user;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @author Author
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('action-channel-one');
         return [new Channel('action-channel-three')];
    }

    /**
     * Get the data to broadcast.
     *
     * @author Author
     *
     * @return array
     */
    public function broadcastWith()
    {
        
        return [
            'actionId' => $this->actionId,
            'actionData' => $this->actionData,
            'sender'=>$this->user
        ];
    }

}
