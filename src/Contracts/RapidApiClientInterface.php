<?php

namespace Abdasis\LaravelRapidApi\Contracts;

use Abdasis\LaravelRapidApi\Http\RapidApiResponse;

interface RapidApiClientInterface
{
    /**
     * Kirim GET request ke endpoint API.
     */
    public function get(string $url, array $query = [], array $headers = []): RapidApiResponse;

    /**
     * Kirim POST request ke endpoint API.
     */
    public function post(string $url, array $data = [], array $headers = []): RapidApiResponse;

    /**
     * Kirim PUT request ke endpoint API.
     */
    public function put(string $url, array $data = [], array $headers = []): RapidApiResponse;

    /**
     * Kirim PATCH request ke endpoint API.
     */
    public function patch(string $url, array $data = [], array $headers = []): RapidApiResponse;

    /**
     * Kirim DELETE request ke endpoint API.
     */
    public function delete(string $url, array $headers = []): RapidApiResponse;

    /**
     * Set RapidAPI host untuk request ini.
     */
    public function withHost(string $host): static;

    /**
     * Tambahkan header tambahan untuk request ini.
     */
    public function withHeaders(array $headers): static;

    /**
     * Nonaktifkan caching untuk request ini.
     */
    public function withoutCache(): static;
}
