<?php

namespace Tests;

use Rockbuzz\SpClient\Client;
use Illuminate\Support\Facades\Http;
use Rockbuzz\SpClient\Data\{Tags, Tag, Links, Meta};

class ClientTest extends TestCase
{
    protected $baseUrl;

    public function setUp(): void
    {
        parent::setUp();

        $this->baseUrl = config('sp_client.base_uri');
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
                    "update_at" => "2020-03-23 12:44:14"
                ],
                [
                    "id" => 2,
                    "name" => "Test Tag II",
                    "created_at" => "2020-03-23 12:44:14",
                    "update_at" => "2020-03-23 12:44:14"
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
            $fullUrl =>  Http::response(json_encode($data), 200)
        ]);

        $tags = $this->newClient()->tags();

        $this->assertInstanceOf(Tags::class, $tags);
        $this->assertInstanceOf(Tag::class, $tags->data[0]);
        $this->assertInstanceOf(Links::class, $tags->links);
        $this->assertInstanceOf(Meta::class, $tags->meta);
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
                "update_at" => "2020-03-23 12:44:14"
            ]
        ];

        Http::fake([
            $fullUrl =>  Http::response(json_encode($data), 200)
        ]);

        $tag = $this->newClient()->tag(1);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($tag->id, 1);
        $this->assertEquals($tag->name, 'Test Tag');
        $this->assertEquals($tag->created_at, '2020-03-23 12:44:14');
        $this->assertEquals($tag->update_at, '2020-03-23 12:44:14');
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
                "update_at" => "2020-03-23 12:44:14"
            ]
        ];

        Http::fake([
            $fullUrl =>  Http::response(json_encode($data), 201)
        ]);

        $tag = $this->newClient()->addTag(['name' => 'Test Tag']);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals($tag->id, 1);
        $this->assertEquals($tag->name, 'Test Tag');
        $this->assertEquals($tag->created_at, '2020-03-23 12:44:14');
        $this->assertEquals($tag->update_at, '2020-03-23 12:44:14');
    }

    protected function newClient(): Client
    {
        return new Client;
    }
}
