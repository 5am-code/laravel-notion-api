<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

/**
 * Class HandlingException
 * @package FiveamCode\LaravelNotionApi\Exceptions
 */
class HandlingException extends LaravelNotionAPIException
{

    /**
     * @param string $message
     * @param array $payload
     * @return HandlingException
     */
    public static function instance(string $message, array $payload = []): HandlingException
    {
        $e = new HandlingException($message);
        $e->payload = $payload;

        return $e;
    }
}