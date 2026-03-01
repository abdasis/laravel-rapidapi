<?php

namespace Abdasis\LaravelRapidApi\Exceptions;

class RapidApiConnectionException extends RapidApiException
{
    public static function timeout(string $url): static
    {
        return new static("Request ke {$url} timeout. Cek koneksi internet atau naikkan RAPIDAPI_TIMEOUT.");
    }

    public static function connectionFailed(string $url, string $reason): static
    {
        return new static("Gagal terhubung ke {$url}: {$reason}");
    }
}
