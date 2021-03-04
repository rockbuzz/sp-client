<?php

namespace Rockbuzz\SpClient\Events;

use Rockbuzz\SpClient\Data\Tag;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\{PrivateChannel, InteractsWithSockets};

class TagCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
