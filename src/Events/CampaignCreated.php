<?php

namespace Rockbuzz\SpClient\Events;

use Rockbuzz\SpClient\Data\Campaign;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CampaignCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Campaign */
    public $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
