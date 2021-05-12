<?php

namespace Tests;

use Carbon\Carbon;
use Rockbuzz\SpClient\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;
use Rockbuzz\SpClient\Data\{Tag, Campaign, Subscriber};

class ClientTest extends TestCase
{
    protected $baseUrl;

    public function setUp(): void
    {
        parent::setUp();

        $this->baseUrl = config('sp_client.base_uri');
    }

    /** @test */
    public function it_should_return_campaigns()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/campaigns?page=1";

        $data = [
            'data' => [
                [
                    "id" => 1,
                    "name" => "name",
                    "subject" => "My Campaign Subject",
                    "content" => "My Email Content",
                    "status_id" => 1,
                    "template_id" => 1,
                    "email_service_id" => 1,
                    "from_name" => "SendPortal",
                    "from_email" => "test@sendportal.io",
                    "is_open_tracking" => true,
                    "is_click_tracking" => true,
                    "sent_count" => 0,
                    "open_count" => 0,
                    "click_count" => 0,
                    "send_to_all" => true,
                    "tags" => [],
                    "save_as_draft" => false,
                    "scheduled_at" => "2020-07-24 08:46:54",
                    "created_at" => "2020-07-24 08:23:38",
                    "updated_at" => "2020-07-24 09:43:42"
                ],
                [
                    "id" => 2,
                    "name" => "name",
                    "subject" => "My Campaign Subject 2",
                    "content" => "My Email Content 2",
                    "status_id" => 1,
                    "template_id" => 1,
                    "email_service_id" => 1,
                    "from_name" => "SendPortal",
                    "from_email" => "test@sendportal.io",
                    "is_open_tracking" => true,
                    "is_click_tracking" => true,
                    "sent_count" => 0,
                    "open_count" => 0,
                    "click_count" => 0,
                    "send_to_all" => true,
                    "tags" => [],
                    "save_as_draft" => false,
                    "scheduled_at" => "2020-07-24 08:46:54",
                    "created_at" => "2020-07-24 08:23:38",
                    "updated_at" => "2020-07-24 09:43:42"
                ]
            ],
            'links' => [
                'first' => $fullUrl,
                'last' => $fullUrl,
                'prev' => null,
                'next' => null
            ],
            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'path' => $fullUrl,
                'per_page' => 25,
                'to' => 1,
                'total' => 1
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $result = $this->newClient()->campaigns();

        $this->assertInstanceOf(Campaign::class, $result['data'][0]);
    }

    /** @test */
    public function it_should_return_campaign()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/campaigns/1";

        $data = [
            'data' => [
                "id" => 1,
                "name" => "name",
                "subject" => "My Campaign Subject",
                "content" => "My Email Content",
                "status_id" => 1,
                "template_id" => 1,
                "email_service_id" => 1,
                "from_name" => "SendPortal",
                "from_email" => "test@sendportal.io",
                "is_open_tracking" => true,
                "is_click_tracking" => true,
                "sent_count" => 0,
                "open_count" => 0,
                "click_count" => 0,
                "send_to_all" => true,
                "tags" => [],
                "save_as_draft" => false,
                "scheduled_at" => "2020-07-24 08:46:54",
                "created_at" => "2020-07-24 08:23:38",
                "updated_at" => "2020-07-24 09:43:42"
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $campaign = $this->newClient()->campaign(1);

        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertEquals($campaign->id, 1);
        $this->assertEquals($campaign->name, 'name');
        $this->assertEquals($campaign->created_at, '2020-07-24 08:23:38');
        $this->assertEquals($campaign->updated_at, '2020-07-24 09:43:42');
        $this->assertInstanceOf(Carbon::class, $campaign->createdAt());
        $this->assertInstanceOf(Carbon::class, $campaign->updatedAt());
    }

    /** @test */
    public function it_should_return_campaign_created()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/campaigns";

        $data = [
            'data' => [
                "id" => 1,
                "name" => "name",
                "subject" => "My Campaign Subject",
                "content" => "My Email Content",
                "status_id" => 1,
                "template_id" => 1,
                "email_service_id" => 1,
                "from_name" => "SendPortal",
                "from_email" => "test@sendportal.io",
                "is_open_tracking" => true,
                "is_click_tracking" => true,
                "sent_count" => 0,
                "open_count" => 0,
                "click_count" => 0,
                "send_to_all" => true,
                "tags" => [],
                "save_as_draft" => false,
                "scheduled_at" => "2020-07-24 08:46:54",
                "created_at" => "2020-07-24 08:23:38",
                "updated_at" => "2020-07-24 09:43:42"
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 201)
        ]);

        $campaign = $this->newClient()->addCampaign($data['data']);

        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertEquals($campaign->id, 1);
        $this->assertEquals($campaign->subject, 'My Campaign Subject');
    }

    /** @test */
    public function it_should_return_campaign_updated()
    {
        $data = [
            'data' => [
                "id" => 1,
                "name" => "Test Campaign Updated",
                "subject" => "My Campaign Subject",
                "content" => "My Email Content",
                "status_id" => 1,
                "template_id" => 1,
                "email_service_id" => 1,
                "from_name" => "SendPortal",
                "from_email" => "test@sendportal.io",
                "is_open_tracking" => true,
                "is_click_tracking" => true,
                "sent_count" => 0,
                "open_count" => 0,
                "click_count" => 0,
                "send_to_all" => true,
                "tags" => [],
                "save_as_draft" => false,
                "scheduled_at" => "2020-07-24 08:46:54",
                "created_at" => "2020-07-24 08:23:38",
                "updated_at" => "2020-07-24 09:43:42"
            ]
        ];

        Http::fake(function () use ($data) {
            return Http::response(json_encode($data), 200);
        });

        $campaign = $this->newClient()->changeCampaign(1, ['name' => 'Test Campaign Updated']);

        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertEquals($campaign->id, 1);
        $this->assertEquals($campaign->name, 'Test Campaign Updated');
        $this->assertEquals($campaign->subject, 'My Campaign Subject');
        $this->assertEquals($campaign->created_at, '2020-07-24 08:23:38');
        $this->assertEquals($campaign->updated_at, '2020-07-24 09:43:42');
    }

    /** @test */
    public function it_should_return_an_validation_exception_when_add_campaign_data_is_missing()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/campaigns";

        $data = [
            'message' => 'The given data was invalid.',
            'errors' => [
                "name" => [
                    'The name field is required.'
                ]
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 422)
        ]);

        $this->expectException(ValidationException::class);

        $this->newClient()->addCampaign([]);
    }

    /** @test */
    public function it_should_return_an_request_exception_when_add_campaign_is_different_from_422()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/campaigns";

        Http::fake([
            $fullUrl => Http::response(json_encode([]), 500)
        ]);

        $this->expectException(RequestException::class);

        $this->newClient()->addCampaign([]);
    }

    /** @test */
    public function it_should_return_tags()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/tags?page=1";

        $data = [
            'data' => [
                [
                    "id" => 1,
                    "name" => "Test Tag",
                    "created_at" => "2020-03-23 12:44:14",
                    "updated_at" => "2020-03-23 12:44:14"
                ],
                [
                    "id" => 2,
                    "name" => "Test Tag II",
                    "created_at" => "2020-03-23 12:44:14",
                    "updated_at" => "2020-03-23 12:44:14"
                ]
            ],
            'links' => [
                'first' => $fullUrl,
                'last' => $fullUrl,
                'prev' => null,
                'next' => null
            ],
            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'path' => $fullUrl,
                'per_page' => 25,
                'to' => 1,
                'total' => 1
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $result = $this->newClient()->tags();

        $this->assertInstanceOf(Tag::class, $result['data'][0]);
    }

    /** @test */
    public function it_should_return_tag()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/tags/1";

        $data = [
            'data' => [
                "id" => 1,
                "name" => "Test Tag",
                "created_at" => "2020-03-23 12:44:14",
                "updated_at" => "2020-03-23 12:44:14"
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $tag = $this->newClient()->tag(1);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($tag->id, 1);
        $this->assertEquals($tag->name, 'Test Tag');
        $this->assertEquals($tag->created_at, '2020-03-23 12:44:14');
        $this->assertEquals($tag->updated_at, '2020-03-23 12:44:14');
        $this->assertInstanceOf(Carbon::class, $tag->createdAt());
        $this->assertInstanceOf(Carbon::class, $tag->updatedAt());
    }

    /** @test */
    public function it_should_return_tag_created()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/tags";

        $data = [
            'data' => [
                "id" => 1,
                "name" => "Test Tag",
                "created_at" => "2020-03-23 12:44:14",
                "updated_at" => "2020-03-23 12:44:14"
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 201)
        ]);

        $tag = $this->newClient()->addTag(['name' => 'Test Tag']);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($tag->id, 1);
        $this->assertEquals($tag->name, 'Test Tag');
        $this->assertEquals($tag->created_at, '2020-03-23 12:44:14');
        $this->assertEquals($tag->updated_at, '2020-03-23 12:44:14');
    }

    /** @test */
    public function it_should_return_tag_updated()
    {
        $data = [
            'data' => [
                "id" => 1,
                "name" => "Test Tag Updated",
                "created_at" => "2020-03-23 12:44:14",
                "updated_at" => "2020-03-23 12:44:14"
            ]
        ];

        Http::fake(function () use ($data) {
            return Http::response(json_encode($data), 200);
        });

        $tag = $this->newClient()->changeTag(1, ['name' => 'Test Tag Updated']);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($tag->id, 1);
        $this->assertEquals($tag->name, 'Test Tag Updated');
        $this->assertEquals($tag->created_at, '2020-03-23 12:44:14');
        $this->assertEquals($tag->updated_at, '2020-03-23 12:44:14');
    }

    /** @test */
    public function it_should_return_subscribers_from_tag()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/tags/1/subscribers?page=1";

        $data = [
            'data' => [
                [
                    "id" => 1,
                    "first_name" => "Test",
                    "last_name" => "Subscriber",
                    "email" => "testsubscriber@example.com",
                    "unsubscribed_at" => null,
                    "created_at" => "2020-03-23 13:44:09",
                    "updated_at" => "2020-03-23 13:44:09"
                ],
                [
                    "id" => 2,
                    "first_name" => "Test",
                    "last_name" => "Subscriber Two",
                    "email" => "testsubscriber2@example.com",
                    "unsubscribed_at" => "2020-08-02 08:07:08",
                    "created_at" => "2020-03-23 13:50:39",
                    "updated_at" => "2020-03-23 13:50:39"
                ]
            ],
            'links' => [
                'first' => $fullUrl,
                'last' => $fullUrl,
                'prev' => null,
                'next' => null
            ],
            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'path' => $fullUrl,
                'per_page' => 25,
                'to' => 1,
                'total' => 1
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $result = $this->newClient()->subscribersFromTag(1);

        $this->assertInstanceOf(Subscriber::class, $result['data'][0]);
    }

    /** @test */
    public function it_should_delete_and_return_subscribers_from_tag()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/tags/1/subscribers";

        $data = [
            'data' => [
                [
                    "id" => 1,
                    "first_name" => "Test",
                    "last_name" => "Subscriber",
                    "email" => "testsubscriber@example.com",
                    "unsubscribed_at" => null,
                    "created_at" => "2020-03-23 13:44:09",
                    "updated_at" => "2020-03-23 13:44:09"
                ],
                [
                    "id" => 2,
                    "first_name" => "Test",
                    "last_name" => "Subscriber Two",
                    "email" => "testsubscriber2@example.com",
                    "unsubscribed_at" => "2020-08-02 08:07:08",
                    "created_at" => "2020-03-23 13:50:39",
                    "updated_at" => "2020-03-23 13:50:39"
                ]
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $result = $this->newClient()->deleteSubscribersFromTag(1, [1, 2]);

        $this->assertInstanceOf(Subscriber::class, $result['data'][0]);
    }

    /** @test */
    public function it_should_return_subscribers()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/subscribers?page=1";

        $data = [
            'data' => [
                [
                    "id" => 1,
                    "first_name" => "Test",
                    "last_name" => "Subscriber",
                    "email" => "testsubscriber@example.com",
                    "unsubscribed_at" => null,
                    "created_at" => "2020-03-23 13:44:09",
                    "updated_at" => "2020-03-23 13:44:09"
                ],
                [
                    "id" => 2,
                    "first_name" => "Test",
                    "last_name" => "Subscriber Two",
                    "email" => "testsubscriber2@example.com",
                    "unsubscribed_at" => "2020-08-02 08:07:08",
                    "created_at" => "2020-03-23 13:50:39",
                    "updated_at" => "2020-03-23 13:50:39"
                ]
            ],
            'links' => [
                'first' => $fullUrl,
                'last' => $fullUrl,
                'prev' => null,
                'next' => null
            ],
            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'path' => $fullUrl,
                'per_page' => 25,
                'to' => 1,
                'total' => 1
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $result = $this->newClient()->subscribers();

        $this->assertInstanceOf(Subscriber::class, $result['data'][0]);
    }

    /** @test */
    public function it_should_return_subscriber()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/subscribers/1";

        $data = [
            'data' => [
                "id" => 1,
                "first_name" => "Test",
                "last_name" => "Subscriber",
                "email" => "testsubscriber@example.com",
                "unsubscribed_at" => null,
                "created_at" => "2020-03-23 13:44:09",
                "updated_at" => "2020-03-23 13:44:09"
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 200)
        ]);

        $subscriber = $this->newClient()->subscriber(1);

        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals($subscriber->id, 1);
        $this->assertEquals($subscriber->first_name, 'Test');
        $this->assertEquals($subscriber->last_name, 'Subscriber');
        $this->assertEquals($subscriber->unsubscribed_at, null);
        $this->assertEquals($subscriber->created_at, '2020-03-23 13:44:09');
        $this->assertEquals($subscriber->updated_at, '2020-03-23 13:44:09');
    }

    /** @test */
    public function it_should_return_subscriber_created()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/subscribers";

        $data = [
            'data' => [
                "id" => 1,
                "first_name" => "Test",
                "last_name" => "Subscriber",
                "email" => "testsubscriber@example.com",
                "unsubscribed_at" => null,
                "created_at" => "2020-03-23 13:44:09",
                "updated_at" => "2020-03-23 13:44:09"
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 201)
        ]);

        $subscriber = $this->newClient()->addSubscriber(['name' => 'Test Tag']);

        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals($subscriber->id, 1);
        $this->assertEquals($subscriber->first_name, 'Test');
        $this->assertEquals($subscriber->last_name, 'Subscriber');
        $this->assertEquals($subscriber->unsubscribed_at, null);
        $this->assertEquals($subscriber->created_at, '2020-03-23 13:44:09');
        $this->assertEquals($subscriber->updated_at, '2020-03-23 13:44:09');
    }

    /** @test */
    public function it_should_return_subscriber_updated()
    {
        $data = [
            'data' => [
                "id" => 1,
                "first_name" => "Test Updated",
                "last_name" => "Subscriber",
                "email" => "testsubscriber@example.com",
                "unsubscribed_at" => null,
                "created_at" => "2020-03-23 13:44:09",
                "updated_at" => "2020-03-23 13:44:09"
            ]
        ];

        Http::fake(function () use ($data) {
            return Http::response(json_encode($data), 200);
        });

        $subscriber = $this->newClient()->changeSubscriber(1, [
            "first_name" => "Test Updated",
            "last_name" => "Subscriber",
            "email" => "testsubscriber@example.com",
            "unsubscribed_at" => null
        ]);

        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals($subscriber->id, 1);
        $this->assertEquals($subscriber->first_name, 'Test Updated');
        $this->assertEquals($subscriber->last_name, 'Subscriber');
        $this->assertEquals($subscriber->unsubscribed_at, null);
        $this->assertEquals($subscriber->created_at, '2020-03-23 13:44:09');
        $this->assertEquals($subscriber->updated_at, '2020-03-23 13:44:09');
    }

    /** @test */
    public function it_should_return_subscriber_added_tags()
    {
        $fullUrl = "{$this->baseUrl}/api/v1/subscribers/1/tags";

        $data = [
            'data' => [
                [
                    "id" => 1,
                    "name" => "Test Tag",
                    "created_at" => "2020-03-23 12:44:14",
                    "updated_at" => "2020-03-23 12:44:14"
                ],
                [
                    "id" => 2,
                    "name" => "Test Tag 2",
                    "created_at" => "2020-03-22 12:44:14",
                    "updated_at" => "2020-03-22 12:44:14"
                ]
            ]
        ];

        Http::fake([
            $fullUrl => Http::response(json_encode($data), 201)
        ]);

        $tags = $this->newClient()->addTagsFromSubscriber(1, [1, 2]);

        $this->assertIsArray($tags);
        $this->assertEquals($tags['data'][0]->id, 1);
        $this->assertEquals($tags['data'][1]->id, 2);
    }

    protected function newClient(): Client
    {
        return new Client;
    }
}
