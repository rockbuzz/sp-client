<?php

namespace Rockbuzz\SpClient;

use Rockbuzz\SpClient\Api;
use Rockbuzz\SpClient\Data\Subscriber;
use Rockbuzz\SpClient\Data\Tag;
use Illuminate\Support\Collection;
use Rockbuzz\SpClient\Data\Campaign;
use Rockbuzz\SpClient\Events\TagCreated;
use Rockbuzz\SpClient\Events\CampaignCreated;
use Rockbuzz\SpClient\Events\SubscriberCreated;

class Client
{
    /**
     * @var Api
     */
    protected $api;

    public function __construct(Api $api = null)
    {
        $this->api = $api ?? new Api;
    }

    /**
     * @inheritDoc
     */
    public function campaigns(int $page = 1): array
    {
        return collect(
            $this->api->get("/api/v1/campaigns?page={$page}")->json()
        )->map(function ($items, $key) {
            if ('data' === $key) {
                return Campaign::arrayOf($items);
            }
            return $items;
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function campaign(int $id): Campaign
    {
        return new Campaign($this->api->get("/api/v1/campaigns/{$id}")->json()['data']);
    }

    /**
     * @inheritDoc
     */
    public function addCampaign(array $data): Campaign
    {
        return tap(
            new Campaign($this->api->post("/api/v1/campaigns", $data)['data']),
            function ($tag) {
                CampaignCreated::dispatch($tag);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function tags(int $page = 1): array
    {
        return collect(
            $this->api->get("/api/v1/tags?page={$page}")->json()
        )->map(function ($items, $key) {
            if ('data' === $key) {
                return Tag::arrayOf($items);
            }
            return $items;
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function tag(int $id): Tag
    {
        return new Tag($this->api->get("/api/v1/tags/{$id}")->json()['data']);
    }

    /**
     * @inheritDoc
     */
    public function addTag(array $data): Tag
    {
        return tap(
            new Tag($this->api->post("/api/v1/tags", $data)['data']),
            function ($tag) {
                TagCreated::dispatch($tag);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function subscribers(int $page = 1): array
    {
        return collect(
            $this->api->get("/api/v1/subscribers?page={$page}")->json()
        )->map(function ($items, $key) {
            if ('data' === $key) {
                return Subscriber::arrayOf($items);
            }
            return $items;
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function subscriber(int $id): Subscriber
    {
        return new Subscriber($this->api->get("/api/v1/subscribers/{$id}")->json()['data']);
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(array $data): Subscriber
    {
        return tap(
            new Subscriber($this->api->post("/api/v1/subscribers", $data)['data']), 
            function ($subscriber) {
                SubscriberCreated::dispatch($subscriber);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function send(int $id): array
    {
        return $this->api->post("/api/v1/campaigns/{$id}/send", [])['data'];
    }
}
