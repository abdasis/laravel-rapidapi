# Laravel RapidAPI Client

Laravel package untuk mengonsumsi API dari [RapidAPI](https://rapidapi.com) dengan mudah. Dilengkapi dengan caching, retry otomatis, logging, dan base class `ApiEndpoint` untuk membuat wrapper API yang rapi.

## Instalasi

```bash
composer require abdasis/laravel-rapidapi
```

Package ini menggunakan Laravel Package Auto-Discovery — service provider terdaftar otomatis.

### Publish konfigurasi (opsional)

```bash
php artisan vendor:publish --tag=rapidapi-config
```

## Konfigurasi

Tambahkan ke `.env`:

```env
RAPIDAPI_KEY=your_rapidapi_key_here

# Opsional
RAPIDAPI_TIMEOUT=30
RAPIDAPI_RETRY_TIMES=3
RAPIDAPI_RETRY_SLEEP=100
RAPIDAPI_CACHE_ENABLED=false
RAPIDAPI_CACHE_TTL=3600
RAPIDAPI_LOGGING_ENABLED=false
```

## Penggunaan

### Facade

```php
use Abdasis\LaravelRapidApi\Facades\RapidApi;

$response = RapidApi::forHost('open-weather-map.p.rapidapi.com')
    ->get('https://open-weather-map.p.rapidapi.com/weather', [
        'q' => 'Jakarta',
    ]);

$data = $response->json();
```

### Dependency Injection

```php
use Abdasis\LaravelRapidApi\RapidApiClient;

class WeatherService
{
    public function __construct(
        private readonly RapidApiClient $client
    ) {}

    public function current(string $city): array
    {
        $response = $this->client
            ->forHost('open-weather-map.p.rapidapi.com')
            ->get('https://open-weather-map.p.rapidapi.com/weather', [
                'q' => $city,
            ]);

        return $response->json();
    }
}
```

### ApiEndpoint — Base Class

Buat wrapper API yang spesifik dengan extend `ApiEndpoint`:

```php
use Abdasis\LaravelRapidApi\Support\ApiEndpoint;

class TikTokApi extends ApiEndpoint
{
    protected string $host = 'tiktok-downloader.p.rapidapi.com';
    protected string $baseUrl = 'https://tiktok-downloader.p.rapidapi.com';

    public function getVideo(string $url): array
    {
        return $this->get('/vid/index', ['url' => $url])->json();
    }
}
```

Daftarkan di service container:

```php
$this->app->singleton(TikTokApi::class);
```

## Method yang Tersedia

### `RapidApiClient`

| Method | Deskripsi |
|---|---|
| `get(url, query, headers)` | Kirim GET request |
| `post(url, data, headers)` | Kirim POST request |
| `put(url, data, headers)` | Kirim PUT request |
| `patch(url, data, headers)` | Kirim PATCH request |
| `delete(url, headers)` | Kirim DELETE request |
| `forHost(host)` | Set X-RapidAPI-Host header |
| `request()` | Buat `RapidApiPendingRequest` |

### `RapidApiPendingRequest`

| Method | Deskripsi |
|---|---|
| `withHost(host)` | Set host untuk request ini |
| `withHeaders(headers)` | Tambahkan header custom |
| `withoutCache()` | Skip cache untuk request ini |
| `get / post / put / patch / delete` | Kirim request |

### `RapidApiResponse`

| Method | Deskripsi |
|---|---|
| `json(key, default)` | Ambil body sebagai array |
| `body()` | Ambil body sebagai string |
| `status()` | HTTP status code |
| `successful()` | `true` jika 2xx |
| `failed()` | `true` jika 4xx/5xx |
| `rateLimited()` | `true` jika 429 |
| `unauthorized()` | `true` jika 401 |
| `notFound()` | `true` jika 404 |

## Penanganan Error

```php
use Abdasis\LaravelRapidApi\Exceptions\RapidApiException;
use Abdasis\LaravelRapidApi\Exceptions\RapidApiConnectionException;

try {
    $response = RapidApi::get('https://api.example.com/data');
} catch (RapidApiConnectionException $e) {
    // Timeout atau gagal koneksi
    logger()->error($e->getMessage());
} catch (RapidApiException $e) {
    // Response 4xx/5xx
    logger()->error($e->getMessage(), [
        'status' => $e->response?->status(),
    ]);
}
```

## Caching

Aktifkan cache untuk mengurangi API call:

```env
RAPIDAPI_CACHE_ENABLED=true
RAPIDAPI_CACHE_TTL=3600
RAPIDAPI_CACHE_STORE=redis
```

Cache otomatis digunakan pada GET request. Nonaktifkan per-request:

```php
RapidApi::request()->withoutCache()->get('https://...');
```

## Testing

```bash
composer test
```

## Lisensi

MIT
