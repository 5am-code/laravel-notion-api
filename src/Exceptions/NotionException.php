<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

use Illuminate\Http\Client\Response;

/**
 * Class NotionException.
 */
class NotionException extends LaravelNotionAPIException
{
    /**
     * @param  string  $message
     * @param  array  $payload
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
     * @param  Response  $response
     * @return NotionException
     */
    public static function fromResponse(Response $response): NotionException
    {
        $responseBody = json_decode($response->getBody()->getContents(), true);

        $errorCode = $errorMessage = '';
        if (array_key_exists('code', $responseBody)) {
            $errorCode = "({$responseBody['code']})";
        }

        if (array_key_exists('code', $responseBody)) {
            $errorMessage = "({$responseBody['message']})";
        }

        $message = "{$response->getReasonPhrase()}: {$errorCode} {$errorMessage}";

        return new NotionException(
            $message,
            $response->status(),
            $response->toException()
        );
    }
}
