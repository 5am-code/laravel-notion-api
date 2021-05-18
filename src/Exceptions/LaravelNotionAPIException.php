<?php

namespace FiveamCode\LaravelNotionApi\Exceptions;

/**
 * Class LaravelNotionAPIException
 *
 * Bundles all exceptions thrown by the
 * package; cannot be thrown by itself.
 *
 * @package FiveamCode\LaravelNotionApi\Exceptions
 */
abstract class LaravelNotionAPIException extends \Exception
{
    /**
     * Provides - if available - useful information to understand this exception better.
     *
     * @var array
     */
    protected array $payload = [];

    /**
     * Handy method to create a *Exception with payload.
     *
     * @param string $message
     * @param array $payload
     * @return HandlingException
     */
    public abstract static function instance(string $message, array $payload = []): LaravelNotionAPIException;


    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}