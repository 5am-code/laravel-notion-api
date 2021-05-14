<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

use Exception;

class WrapperException extends Exception
{

    /**
     * Provides - if available - useful information to understand this exception faster.
     *
     * @var array
     */
    private array $payload = [];

    /**
     * Handy method to create a WrapperException with payload.
     *
     * @param string $message
     * @param array $payload
     * @return WrapperException
     */
    public static function instance(string $message, array $payload = []): WrapperException
    {
        $e = new WrapperException($message);
        $e->payload = $payload;

        return $e;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

}