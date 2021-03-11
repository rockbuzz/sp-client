<?php

namespace Rockbuzz\SpClient;

use Rockbuzz\SpClient\Api;
use Rockbuzz\SpClient\Events\{
    TagCreated,
    CampaignCreated,
    SubscriberCreated,
    TagUpdated,
    SubscriberUpdated
};
use Rockbuzz\SpClient\Data\{Subscriber, Tag, Campaign};

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
            $this->api->get(config('sp_client.uri.campaigns')."?page={$page}")->json(),
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
        return new Campaign($this->api->get(config('sp_client.uri.campaigns')."/{$id}")->json()['data']);
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
            new Campaign($this->api->post(config('sp_client.uri.campaigns'), $data)['data']),
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
            $this->api->get(config('sp_client.uri.tags')."?page={$page}")->json(),
            Tag::class
        );
    }

    /**
     * Returns all tags without pagination
     *
     * @param integer $page
     * @return array
     */
    public function allTags(): array
    {
        return $this->mountDataResult(
            $this->api->get("/api/v1/all-tags")->json(),
            Tag::class
        )['data'];
    }

    /**
     * Return tag by ID
     *
     * @param integer $id
     * @return Tag
     */
    public function tag(int $id): Tag
    {
        return new Tag($this->api->get(config('sp_client.uri.tags')."/{$id}")->json()['data']);
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
            new Tag($this->api->post(config('sp_client.uri.tags'), $data)['data']),
            function ($tag) {
                TagCreated::dispatch($tag);
            }
        );
    }

    /**
     * Change tag
     *
     * @param int $id
     * @param array $data
     * @return Tag
     */
    public function changeTag(int $id, array $data): Tag
    {
        return tap(
            new Tag($this->api->put(config('sp_client.uri.tags')."/{$id}", $data)['data']),
            function ($tag) {
                TagUpdated::dispatch($tag);
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
            $this->api->get(config('sp_client.uri.subscribers')."?page={$page}")->json(),
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
        return new Subscriber($this->api->get(config('sp_client.uri.subscribers')."/{$id}")->json()['data']);
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
            new Subscriber($this->api->post(config('sp_client.uri.subscribers'), $data)['data']),
            function ($subscriber) {
                SubscriberCreated::dispatch($subscriber);
            }
        );
    }

    /**
     * Change subscriber
     *
     * @param array $data
     * @return Subscriber
     */
    public function changeSubscriber(int $id, array $data): Subscriber
    {
        return tap(
            new Subscriber($this->api->put(config('sp_client.uri.subscribers')."/{$id}", $data)['data']),
            function ($subscriber) {
                SubscriberUpdated::dispatch($subscriber);
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
        return new Campaign($this->api->post(config('sp_client.uri.campaigns')."/{$id}/send", [])['data']);
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
