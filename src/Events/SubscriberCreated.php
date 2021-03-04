<?php

namespace Rockbuzz\SpClient\Events;

use Rockbuzz\SpClient\Data\Subscriber;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SubscriberCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Subscriber */
    public $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
