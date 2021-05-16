<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Entity
{
    private string $id;
    protected array $responseData = [];


    public function __construct(array $responseData = null)
    {
        $this->setResponseData($responseData);
    }

    protected function setResponseData(array $responseData): void
    {
        if (!Arr::exists($responseData, 'object')) throw WrapperException::instance("invalid json-array: no object given");
        if (!Arr::exists($responseData, 'id')) throw WrapperException::instance("invalid json-array: no id provided");

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
}
