<?php

namespace Abdasis\LaravelRapidApi;

use Illuminate\Http\Client\Factory as HttpFactory;
use Abdasis\LaravelRapidApi\Http\RapidApiPendingRequest;
use Abdasis\LaravelRapidApi\Http\RapidApiResponse;

class RapidApiClient
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly array $config
    ) {}

    /**
     * Buat pending request dengan host dan headers default.
     */
    public function request(): RapidApiPendingRequest
    {
        $client = $this->http
            ->baseUrl('')
            ->timeout($this->config['timeout'] ?? 30)
            ->acceptJson();

        return new RapidApiPendingRequest($client, $this->config);
    }

    /**
     * Kirim GET request langsung.
     */
    public function get(string $url, array $query = [], array $headers = []): RapidApiResponse
    {
        return $this->request()
            ->withHeaders($headers)
            ->get($url, $query);
    }

    /**
     * Kirim POST request langsung.
     */
    public function post(string $url, array $data = [], array $headers = []): RapidApiResponse
    {
        return $this->request()
            ->withHeaders($headers)
            ->post($url, $data);
    }

    /**
     * Kirim PUT request langsung.
     */
    public function put(string $url, array $data = [], array $headers = []): RapidApiResponse
    {
        return $this->request()
            ->withHeaders($headers)
            ->put($url, $data);
    }

    /**
     * Kirim PATCH request langsung.
     */
    public function patch(string $url, array $data = [], array $headers = []): RapidApiResponse
    {
        return $this->request()
            ->withHeaders($headers)
            ->patch($url, $data);
    }

    /**
     * Kirim DELETE request langsung.
     */
    public function delete(string $url, array $headers = []): RapidApiResponse
    {
        return $this->request()
            ->withHeaders($headers)
            ->delete($url);
    }

    /**
     * Buat request dengan host spesifik.
     */
    public function forHost(string $host): RapidApiPendingRequest
    {
        return $this->request()->withHost($host);
    }
}
