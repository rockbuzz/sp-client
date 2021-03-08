<?php

namespace Rockbuzz\SpClient;

use Rockbuzz\SpClient\Api;
use Rockbuzz\SpClient\Data\{Subscriber, Tag, Campaign};
use Rockbuzz\SpClient\Events\{TagCreated, CampaignCreated, SubscriberCreated};

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
     * Returns an array with the campaigns data, links and meta indexes
     *
     * @param integer $page
     * @return array
     */
    public function campaigns(int $page = 1): array
    {
        return $this->mountDataResult(
            $this->api->get("/api/v1/campaigns?page={$page}")->json(),
            Campaign::class
        );
    }

    /**
     * Return campaign br ID
     *
     * @param integer $id
     * @return Campaign
     */
    public function campaign(int $id): Campaign
    {
        return new Campaign($this->api->get("/api/v1/campaigns/{$id}")->json()['data']);
    }

    /**
     * Add campaign
     *
     * @param array $data
     * @return Campaign
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
     * Returns an array with the tags data, links and meta indexes
     *
     * @param integer $page
     * @return array
     */
    public function tags(int $page = 1): array
    {
        return $this->mountDataResult(
            $this->api->get("/api/v1/tags?page={$page}")->json(),
            Tag::class
        );
    }

    /**
     * Return tag by ID
     *
     * @param integer $id
     * @return Tag
     */
    public function tag(int $id): Tag
    {
        return new Tag($this->api->get("/api/v1/tags/{$id}")->json()['data']);
    }

    /**
     * Add tag
     *
     * @param array $data
     * @return Tag
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
     * Returns an array with the subscribers data, links and meta indexes
     *
     * @param integer $page
     * @return array
     */
    public function subscribers(int $page = 1): array
    {
        return $this->mountDataResult(
            $this->api->get("/api/v1/subscribers?page={$page}")->json(),
            Subscriber::class
        );
    }

    /**
     * Return subscriber by ID
     *
     * @param integer $id
     * @return Subscriber
     */
    public function subscriber(int $id): Subscriber
    {
        return new Subscriber($this->api->get("/api/v1/subscribers/{$id}")->json()['data']);
    }

    /**
     * Add subscriber
     *
     * @param array $data
     * @return Subscriber
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
     * Send campaign emails 
     *
     * @param integer $id
     * @return Campaign
     */
    public function send(int $id): Campaign
    {
        return new Campaign($this->api->post("/api/v1/campaigns/{$id}/send", [])['data']);
    }

    /**
     * @param array $data
     * @param string $itemType
     * @return array
     */
    protected function mountDataResult(array $data, string $itemType): array
    {
        return collect($data)->mapDataWithType($itemType)->toArray();
    }
}
