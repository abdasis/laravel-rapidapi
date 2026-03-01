<?php

namespace Abdasis\LaravelRapidApi\Support;

use Abdasis\LaravelRapidApi\Http\RapidApiResponse;
use Abdasis\LaravelRapidApi\RapidApiClient;

/**
 * Base class untuk membuat wrapper API yang spesifik.
 *
 * Contoh penggunaan:
 *
 *   class WeatherApi extends ApiEndpoint
 *   {
 *       protected string $host = 'open-weather-map.p.rapidapi.com';
 *       protected string $baseUrl = 'https://open-weather-map.p.rapidapi.com';
 *
 *       public function current(string $city): RapidApiResponse
 *       {
 *           return $this->get('/weather', ['q' => $city]);
 *       }
 *   }
 */
abstract class ApiEndpoint
{
    /** RapidAPI host untuk API ini (e.g. 'weather-api.p.rapidapi.com') */
    protected string $host = '';

    /** Base URL untuk API ini */
    protected string $baseUrl = '';

    public function __construct(
        protected readonly RapidApiClient $client
    ) {}

    /**
     * Kirim GET request dengan host dan baseUrl yang sudah dikonfigurasi.
     */
    protected function get(string $path, array $query = [], array $headers = []): RapidApiResponse
    {
        return $this->client
            ->forHost($this->host)
            ->withHeaders($headers)
            ->get($this->baseUrl.$path, $query);
    }

    /**
     * Kirim POST request dengan host dan baseUrl yang sudah dikonfigurasi.
     */
    protected function post(string $path, array $data = [], array $headers = []): RapidApiResponse
    {
        return $this->client
            ->forHost($this->host)
            ->withHeaders($headers)
            ->post($this->baseUrl.$path, $data);
    }

    /**
     * Kirim PUT request dengan host dan baseUrl yang sudah dikonfigurasi.
     */
    protected function put(string $path, array $data = [], array $headers = []): RapidApiResponse
    {
        return $this->client
            ->forHost($this->host)
            ->withHeaders($headers)
            ->put($this->baseUrl.$path, $data);
    }

    /**
     * Kirim DELETE request dengan host dan baseUrl yang sudah dikonfigurasi.
     */
    protected function delete(string $path, array $headers = []): RapidApiResponse
    {
        return $this->client
            ->forHost($this->host)
            ->withHeaders($headers)
            ->delete($this->baseUrl.$path);
    }
}
