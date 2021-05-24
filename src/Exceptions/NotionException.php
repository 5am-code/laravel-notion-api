<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

use Illuminate\Http\Client\Response;

/**
 * Class NotionException
 * @package FiveamCode\LaravelNotionApi\Exceptions
 */
class NotionException extends LaravelNotionAPIException
{

    /**
     * @param string $message
     * @param array $payload
     * @return NotionException
     */
    public static function instance(string $message, array $payload = []): NotionException
    {
        $e = new NotionException($message);
        $e->payload = $payload;

        return $e;
    }

    /**
     * Handy method to create a NotionException
     * from a failed request.
     *
     * @param Response $response
     * @return NotionException
     */
    public static function fromResponse(Response $response): NotionException
    {
        return new NotionException(
            $response->getReasonPhrase(), 0,
            $response->toException()
        );
    }

}