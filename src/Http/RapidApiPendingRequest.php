<?php

namespace Abdasis\LaravelRapidApi\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Abdasis\LaravelRapidApi\Exceptions\RapidApiConnectionException;
use Abdasis\LaravelRapidApi\Exceptions\RapidApiException;

class RapidApiPendingRequest
{
    private array $extraHeaders = [];

    private ?string $host = null;

    private bool $skipCache = false;

    public function __construct(
        private readonly PendingRequest $httpClient,
        private readonly array $config
    ) {}

    /**
     * Set RapidAPI host untuk request ini.
     */
    public function withHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Tambahkan header custom untuk request ini.
     */
    public function withHeaders(array $headers): static
    {
        $this->extraHeaders = array_merge($this->extraHeaders, $headers);

        return $this;
    }

    /**
     * Nonaktifkan cache untuk request ini.
     */
    public function withoutCache(): static
    {
        $this->skipCache = true;

        return $this;
    }

    public function get(string $url, array $query = []): RapidApiResponse
    {
        $cacheKey = $this->buildCacheKey('GET', $url, $query);

        if ($this->shouldUseCache() && Cache::store($this->cacheStore())->has($cacheKey)) {
            $this->log('GET', $url, ['source' => 'cache']);

            return Cache::store($this->cacheStore())->get($cacheKey);
        }

        $response = $this->send('GET', $url, ['query' => $query]);

        if ($this->shouldUseCache()) {
            Cache::store($this->cacheStore())->put($cacheKey, $response, $this->config['cache']['ttl']);
        }

        return $response;
    }

    public function post(string $url, array $data = []): RapidApiResponse
    {
        return $this->send('POST', $url, ['json' => $data]);
    }

    public function put(string $url, array $data = []): RapidApiResponse
    {
        return $this->send('PUT', $url, ['json' => $data]);
    }

    public function patch(string $url, array $data = []): RapidApiResponse
    {
        return $this->send('PATCH', $url, ['json' => $data]);
    }

    public function delete(string $url): RapidApiResponse
    {
        return $this->send('DELETE', $url);
    }

    private function send(string $method, string $url, array $options = []): RapidApiResponse
    {
        $headers = $this->buildHeaders();

        $this->log($method, $url);

        try {
            $illuminateResponse = $this->httpClient
                ->withHeaders($headers)
                ->retry($this->config['retry']['times'], $this->config['retry']['sleep'])
                ->send($method, $url, $options);

            $response = new RapidApiResponse($illuminateResponse);

            $this->log($method, $url, ['status' => $response->status()]);

            if ($response->failed()) {
                throw RapidApiException::fromResponse($response);
            }

            return $response;
        } catch (RapidApiException $e) {
            throw $e;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw RapidApiConnectionException::connectionFailed($url, $e->getMessage());
        }
    }

    private function buildHeaders(): array
    {
        $headers = $this->config['headers'] ?? [];

        if ($this->host !== null) {
            $headers['X-RapidAPI-Host'] = $this->host;
        }

        return array_merge($headers, $this->extraHeaders);
    }

    private function buildCacheKey(string $method, string $url, array $params = []): string
    {
        return 'rapidapi_'.md5($method.$url.serialize($params).($this->host ?? ''));
    }

    private function shouldUseCache(): bool
    {
        return ! $this->skipCache && ($this->config['cache']['enabled'] ?? false);
    }

    private function cacheStore(): ?string
    {
        return $this->config['cache']['store'] ?? null;
    }

    private function log(string $method, string $url, array $context = []): void
    {
        if (! ($this->config['logging']['enabled'] ?? false)) {
            return;
        }

        Log::channel($this->config['logging']['channel'] ?? 'stack')
            ->info("[RapidAPI] {$method} {$url}", $context);
    }
}
