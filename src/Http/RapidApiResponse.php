<?php

namespace Abdasis\LaravelRapidApi\Http;

use Illuminate\Http\Client\Response;

class RapidApiResponse
{
    public function __construct(
        private readonly Response $response
    ) {}

    /**
     * Dapatkan body response sebagai string.
     */
    public function body(): string
    {
        return $this->response->body();
    }

    /**
     * Dapatkan response sebagai array.
     */
    public function json(?string $key = null, mixed $default = null): mixed
    {
        return $this->response->json($key, $default);
    }

    /**
     * Dapatkan response sebagai object.
     */
    public function object(): object
    {
        return $this->response->object();
    }

    /**
     * Dapatkan HTTP status code.
     */
    public function status(): int
    {
        return $this->response->status();
    }

    /**
     * Cek apakah request berhasil (2xx).
     */
    public function successful(): bool
    {
        return $this->response->successful();
    }

    /**
     * Cek apakah terjadi error (4xx atau 5xx).
     */
    public function failed(): bool
    {
        return $this->response->failed();
    }

    /**
     * Cek apakah status 404.
     */
    public function notFound(): bool
    {
        return $this->response->notFound();
    }

    /**
     * Cek apakah status 401 (unauthorized).
     */
    public function unauthorized(): bool
    {
        return $this->response->unauthorized();
    }

    /**
     * Cek apakah status 403 (forbidden).
     */
    public function forbidden(): bool
    {
        return $this->response->forbidden();
    }

    /**
     * Cek apakah status 429 (rate limited).
     */
    public function rateLimited(): bool
    {
        return $this->response->status() === 429;
    }

    /**
     * Dapatkan header tertentu.
     */
    public function header(string $header): string
    {
        return $this->response->header($header);
    }

    /**
     * Dapatkan semua header.
     */
    public function headers(): array
    {
        return $this->response->headers();
    }

    /**
     * Lempar exception jika request gagal.
     */
    public function throw(): static
    {
        $this->response->throw();

        return $this;
    }

    /**
     * Akses response Illuminate langsung jika dibutuhkan.
     */
    public function toIlluminateResponse(): Response
    {
        return $this->response;
    }
}
