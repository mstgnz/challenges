<?php

namespace App\Events;

use App\Jobs\ProcessCallback;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RenewedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct($endpoint, $event)
    {

        $event['type'] = 'Renewed';
        ProcessCallback::dispatch($endpoint . 'calback', $event);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
