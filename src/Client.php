<?php

namespace Rockbuzz\SpClient;

use Rockbuzz\SpClient\Data\Tag;
use Rockbuzz\SpClient\Data\Tags;
use Rockbuzz\SpClient\ApiService;
use Rockbuzz\SpClient\Events\TagCreated;
use Rockbuzz\SpClient\Events\CampaignCreated;
use Rockbuzz\SpClient\Events\SubscriberCreated;

class Client
{
    /**
     * @var ApiService
     */
    protected $api;

    public function __construct(ApiService $api = null)
    {
        $this->api = $api ?? new ApiService;
    }

    /**
     * @inheritDoc
     */
    public function campaigns(int $page = 1): array
    {
        return $this->api->get("/api/v1/campaigns?page={$page}");
    }

    /**
     * @inheritDoc
     */
    public function campaign(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}")['data'];
    }

    /**
     * @inheritDoc
     */
    public function addCampaign(array $data):array
    {
        return tap($this->api->post("/api/v1/campaigns", $data), function ($campaign) {
            CampaignCreated::dispatch($campaign);
        });
    }

    /**
     * @inheritDoc
     */
    public function tags(int $page = 1): Tags
    {
        return Tags::make($this->api->get("/api/v1/tags?page={$page}"));
    }

    /**
     * @inheritDoc
     */
    public function tag(int $id): Tag
    {
        return Tag::fromArray($this->api->get("/api/v1/tags/{$id}")['data']);
    }

    /**
     * @inheritDoc
     */
    public function addTag(array $data): Tag
    {
        return tap(
            Tag::fromArray($this->api->post("/api/v1/tags", $data)['data']),
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
        return $this->api->get("/api/v1/subscribers?page={$page}");
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(array $data): array
    {
        return tap($this->api->post("/api/v1/subscribers", $data), function ($subscriber) {
            SubscriberCreated::dispatch($subscriber);
        });
    }

    /**
     * @inheritDoc
     */
    public function send(int $id): array
    {
        return $this->api->post("/api/v1/campaigns/{$id}/send", [])['data'];
    }
}
