<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RapidAPI Key
    |--------------------------------------------------------------------------
    |
    | API key dari RapidAPI dashboard Anda. Dapatkan di:
    | https://rapidapi.com/developer/apps
    |
    */
    'key' => env('RAPIDAPI_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL RapidAPI
    |--------------------------------------------------------------------------
    */
    'base_url' => 'https://rapidapi.com',

    /*
    |--------------------------------------------------------------------------
    | Default Headers
    |--------------------------------------------------------------------------
    |
    | Header yang selalu dikirim ke setiap request RapidAPI.
    |
    */
    'headers' => [
        'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
        'Content-Type' => 'application/json',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout (detik)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('RAPIDAPI_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Jumlah retry dan delay (milliseconds) saat request gagal.
    |
    */
    'retry' => [
        'times' => env('RAPIDAPI_RETRY_TIMES', 3),
        'sleep' => env('RAPIDAPI_RETRY_SLEEP', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache response untuk mengurangi jumlah API call.
    |
    */
    'cache' => [
        'enabled' => env('RAPIDAPI_CACHE_ENABLED', false),
        'ttl' => env('RAPIDAPI_CACHE_TTL', 3600), // dalam detik
        'store' => env('RAPIDAPI_CACHE_STORE', null), // null = default cache store
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Log semua request dan response untuk debugging.
    |
    */
    'logging' => [
        'enabled' => env('RAPIDAPI_LOGGING_ENABLED', false),
        'channel' => env('RAPIDAPI_LOG_CHANNEL', 'stack'),
    ],
];
