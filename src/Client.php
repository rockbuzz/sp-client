<?php

namespace Rockbuzz\SpClient;

use Rockbuzz\SpClient\Events\{
    CampaignUpdated,
    TagCreated,
    CampaignCreated,
    SubscriberCreated,
    TagUpdated,
    SubscriberUpdated
};
use Rockbuzz\SpClient\Data\{Base, Subscriber, Tag, Campaign};

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

    public function campaignReportIndex(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}/report")->json();
    }

    public function campaignReportRecipients(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}/report/recipients")->json();
    }

    public function campaignReportOpens(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}/report/opens")->json();
    }

    public function campaignReportClicks(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}/report/clicks")->json();
    }

    public function campaignReportBounces(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}/report/bounces")->json();
    }

    public function campaignReportUnsubscribes(int $id): array
    {
        return $this->api->get("/api/v1/campaigns/{$id}/report/unsubscribes")->json();
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
            $this->api->get(config('sp_client.uri.campaigns') . "?page={$page}")->json(),
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
        return new Campaign($this->api->get(config('sp_client.uri.campaigns') . "/{$id}")->json()['data']);
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
     * Change campaign
     *
     * @param int $id
     * @param array $data
     * @return Campaign
     */
    public function changeCampaign(int $id, array $data): Campaign
    {
        return tap(
            new Campaign($this->api->put(config('sp_client.uri.campaigns') . "/{$id}", $data)['data']),
            static function ($campaign) {
                CampaignUpdated::dispatch($campaign);
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
            $this->api->get(config('sp_client.uri.tags') . "?page={$page}")->json(),
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
        return new Tag($this->api->get(config('sp_client.uri.tags') . "/{$id}")->json()['data']);
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
            new Tag($this->api->put(config('sp_client.uri.tags') . "/{$id}", $data)['data']),
            function ($tag) {
                TagUpdated::dispatch($tag);
            }
        );
    }

    /**
     * Returns an array with the subscribers from tag with data, links and meta indexes
     *
     * @param integer $tagId
     * @param integer $page
     * @return array
     */
    public function subscribersFromTag(int $tagId, int $page = 1): array
    {
        return $this->mountDataResult(
            $this->api->get("/api/v1/tags/{$tagId}/subscribers?page={$page}")->json(),
            Subscriber::class
        );
    }

    /**
     * Returns an array deleted subscribers from tag
     *
     * @param integer $tagId
     * @param integer[] $subscribersId
     * @return array
     */
    public function deleteSubscribersFromTag(int $tagId, array $subscribersId): array
    {
        return $this->mountDataResult(
            $this->api->delete(
                "/api/v1/tags/{$tagId}/subscribers",
                ['subscribers' => $subscribersId]
            )->json(),
            Subscriber::class
        );
    }

    /**
     * Returns an array subscribers data, links and meta indexes
     *
     * @param integer $page
     * @return array
     */
    public function subscribers(int $page = 1): array
    {
        return $this->mountDataResult(
            $this->api->get(config('sp_client.uri.subscribers') . "?page={$page}")->json(),
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
        return new Subscriber($this->api->get(config('sp_client.uri.subscribers') . "/{$id}")->json()['data']);
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
            new Subscriber($this->api->put(config('sp_client.uri.subscribers') . "/{$id}", $data)['data']),
            function ($subscriber) {
                SubscriberUpdated::dispatch($subscriber);
            }
        );
    }

    /**
     * Add tags to subscriber
     *
     * @param int $subscriberId
     * @param array $tags
     * @return array
     */
    public function addTagsFromSubscriber(int $subscriberId, array $tags): array
    {
        return $this->mountDataResult(
            $this->api->post(
                config('sp_client.uri.subscribers') . "/{$subscriberId}/tags",
                ['tags' => $tags]
            )->json(),
            Tag::class
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
        return new Campaign($this->api->post(config('sp_client.uri.campaigns') . "/{$id}/send", [])['data']);
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
