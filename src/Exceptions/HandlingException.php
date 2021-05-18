<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

class HandlingException extends LaravelNotionAPIException
{

    public static function instance(string $message, array $payload = []): HandlingException
    {
        $e = new HandlingException($message);
        $e->payload = $payload;

        return $e;
    }
}