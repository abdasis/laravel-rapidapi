<?php

namespace Abdasis\LaravelRapidApi\Tests;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use Abdasis\LaravelRapidApi\Exceptions\RapidApiException;
use Abdasis\LaravelRapidApi\Http\RapidApiResponse;
use Abdasis\LaravelRapidApi\RapidApiClient;
use Abdasis\LaravelRapidApi\RapidApiServiceProvider;

class RapidApiClientTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [RapidApiServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('rapidapi.key', 'test-api-key');
        $app['config']->set('rapidapi.headers', [
            'X-RapidAPI-Key' => 'test-api-key',
        ]);
        $app['config']->set('rapidapi.timeout', 30);
        $app['config']->set('rapidapi.retry', ['times' => 1, 'sleep' => 0]);
        $app['config']->set('rapidapi.cache', ['enabled' => false]);
        $app['config']->set('rapidapi.logging', ['enabled' => false]);
    }

    public function test_get_request_berhasil(): void
    {
        Http::fake([
            'https://api.example.com/test' => Http::response(['status' => 'ok'], 200),
        ]);

        $client = new RapidApiClient(new HttpFactory, config('rapidapi'));
        $response = $client->get('https://api.example.com/test');

        expect($response)->toBeInstanceOf(RapidApiResponse::class)
            ->and($response->successful())->toBeTrue()
            ->and($response->json('status'))->toBe('ok');
    }

    public function test_post_request_berhasil(): void
    {
        Http::fake([
            'https://api.example.com/create' => Http::response(['id' => 1], 201),
        ]);

        $client = new RapidApiClient(new HttpFactory, config('rapidapi'));
        $response = $client->post('https://api.example.com/create', ['name' => 'test']);

        expect($response->json('id'))->toBe(1);
    }

    public function test_lempar_exception_saat_response_error(): void
    {
        Http::fake([
            'https://api.example.com/error' => Http::response(['message' => 'Not found'], 404),
        ]);

        $client = new RapidApiClient(new HttpFactory, config('rapidapi'));

        expect(fn () => $client->get('https://api.example.com/error'))
            ->toThrow(RapidApiException::class);
    }

    public function test_for_host_mengirim_header_host(): void
    {
        Http::fake([
            '*' => Http::response(['ok' => true], 200),
        ]);

        $client = new RapidApiClient(new HttpFactory, config('rapidapi'));
        $client->forHost('weather-api.p.rapidapi.com')
            ->get('https://weather-api.p.rapidapi.com/current');

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-RapidAPI-Host', 'weather-api.p.rapidapi.com');
        });
    }

    public function test_rate_limited_response(): void
    {
        Http::fake([
            '*' => Http::response([], 429),
        ]);

        $client = new RapidApiClient(new HttpFactory, config('rapidapi'));

        try {
            $client->get('https://api.example.com/test');
        } catch (RapidApiException $e) {
            expect($e->response->rateLimited())->toBeTrue();
        }
    }
}
