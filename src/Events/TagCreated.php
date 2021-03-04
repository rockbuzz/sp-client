<?php

namespace Rockbuzz\SpClient\Events;

use Rockbuzz\SpClient\Data\Tag;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TagCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Tag */
    public $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
