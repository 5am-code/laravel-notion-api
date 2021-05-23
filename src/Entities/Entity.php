<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use JsonSerializable;

class Entity implements JsonSerializable
{
    private string $id;
    protected array $responseData = [];


    public function __construct(array $responseData = null)
    {
        $this->setResponseData($responseData);
    }

    protected function setResponseData(array $responseData): void
    {
        if (!Arr::exists($responseData, 'object'))
            throw new HandlingException("invalid json-array: no object given");

        // TODO
        // Currently, the API returns not-found objects with status code 200 -
        // so we have to check here on the given status code in the paylaod,
        // if the object was not found.
        if(
            $responseData['object'] === 'error'
            && Arr::exists($responseData, 'status') && $responseData['status'] === 404
        ) {
            throw NotionException::instance("Not found", compact("responseData"));
        }

        if (!Arr::exists($responseData, 'id')) throw HandlingException::instance("invalid json-array: no id provided");

        $this->responseData = $responseData;
    }

    protected function fillCreatedTime()
    {
        if (Arr::exists($this->responseData, 'created_time')) {
            $this->createdTime = new Carbon($this->responseData['created_time']);
        }
    }

    protected function fillLastEditedTime()
    {
        if (Arr::exists($this->responseData, 'last_edited_time')) {
            $this->lastEditedTime = new Carbon($this->responseData['last_edited_time']);
        }
    }

    protected function fillId()
    {
        $this->id = $this->responseData['id'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRaw(): array
    {
        return $this->responseData;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array {
        return get_object_vars($this);
    }
}
