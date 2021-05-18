<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

use Illuminate\Http\Client\Response;

class NotionException extends LaravelNotionAPIException
{

    /**
     * Handy method to create a NotionException
     * from a failed request.
     *
     * @param string $message
     * @param array $payload
     * @return HandlingException
     */
    public static function fromResponse(Response $response): NotionException
    {
        $e = new NotionException(
            "{$response->getStatusCode()} {$response->getReasonPhrase()}", 0,
            $response->toException()
        );

        return $e;
    }

}