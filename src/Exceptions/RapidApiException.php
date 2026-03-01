<?php

namespace Abdasis\LaravelRapidApi\Exceptions;

use RuntimeException;
use Abdasis\LaravelRapidApi\Http\RapidApiResponse;

class RapidApiException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?RapidApiResponse $response = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function fromResponse(RapidApiResponse $response): static
    {
        $message = sprintf(
            'RapidAPI request gagal dengan status %d: %s',
            $response->status(),
            $response->body()
        );

        return new static($message, $response, $response->status());
    }
}
