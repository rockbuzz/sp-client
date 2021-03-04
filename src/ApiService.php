<?php

namespace Rockbuzz\SpClient;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiService
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

    /**
     * @param string $uri
     * @return array
     * @throws ClientException
     */
    public function get(string $uri): array
    {
        return $this->resolveResponse($this->request->get($uri));
    }

    /**
     * @param string $uri
     * @param array $data
     * @return array
     * @throws ClientException
     */
    public function post(string $uri, array $data): array
    {
        return $this->resolveResponse($this->request->post($uri, $data));
    }

    /**
     * @param string $uri
     * @param array $data
     * @return array
     * @throws ClientException
     */
    public function put(string $uri, array $data): array
    {
        return $this->resolveResponse($this->request->put($uri, $data));
    }

    /**
     * @param string $uri
     * @param array $data
     * @return array
     * @throws ClientException
     */
    public function patch(string $uri, array $data): array
    {
        return $this->resolveResponse($this->request->patch($uri, $data));
    }

    /**
     * @param string $uri
     * @return array
     * @throws ClientException
     */
    public function delete(string $uri): array
    {
        return $this->resolveResponse($this->request->delete($uri));
    }

    /**
     * @param Response $response
     * @return array
     * @throws ClientException
     */
    public function resolveResponse(Response $response): array
    {
        if ($response->successful()) {
            return $response->json();
        }

        throw new ClientException($response['message'], $response->status());
    }
}
