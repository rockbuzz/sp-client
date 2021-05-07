<?php

namespace Rockbuzz\SpClient;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class Api
{
    /**
     * @var PendingRequest
     */
    protected $request;

    public function __construct()
    {
        $this->request = Http::withOptions([
            'base_uri' => config('sp_client.base_uri')
        ])->withToken(config('sp_client.token'))
            ->acceptJson()
            ->asJson();
    }

    public function get(string $uri)
    {
        return $this->request->get($uri)->resolveForClient();
    }

    public function post(string $uri, array $data): Response
    {
        return $this->request->post($uri, $data)->resolveForClient();
    }

    public function put(string $uri, array $data): Response
    {
        return $this->request->put($uri, $data)->resolveForClient();
    }

    public function patch(string $uri, array $data): Response
    {
        return $this->request->patch($uri, $data)->resolveForClient();
    }

    public function delete(string $uri, array $data = []): Response
    {
        return $this->request->delete($uri, $data)->resolveForClient();
    }
}
