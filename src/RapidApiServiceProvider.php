<?php

namespace Abdasis\LaravelRapidApi;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;

class RapidApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/rapidapi.php',
            'rapidapi'
        );

        $this->app->singleton(RapidApiClient::class, function ($app) {
            return new RapidApiClient(
                $app->make(HttpFactory::class),
                $app->make('config')->get('rapidapi', [])
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/rapidapi.php' => config_path('rapidapi.php'),
            ], 'rapidapi-config');
        }
    }
}
