<?php

namespace Abdasis\LaravelRapidApi\Facades;

use Illuminate\Support\Facades\Facade;
use Abdasis\LaravelRapidApi\Http\RapidApiPendingRequest;
use Abdasis\LaravelRapidApi\Http\RapidApiResponse;

/**
 * @method static RapidApiResponse get(string $url, array $query = [], array $headers = [])
 * @method static RapidApiResponse post(string $url, array $data = [], array $headers = [])
 * @method static RapidApiResponse put(string $url, array $data = [], array $headers = [])
 * @method static RapidApiResponse patch(string $url, array $data = [], array $headers = [])
 * @method static RapidApiResponse delete(string $url, array $headers = [])
 * @method static RapidApiPendingRequest request()
 * @method static RapidApiPendingRequest forHost(string $host)
 *
 * @see \Abdasis\LaravelRapidApi\RapidApiClient
 */
class RapidApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Abdasis\LaravelRapidApi\RapidApiClient::class;
    }
}
