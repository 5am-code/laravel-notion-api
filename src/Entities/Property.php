<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;

class Property extends Entity
{
    protected string $title;
    protected string $type;
    protected $rawContent;

    public function __construct(string $title, array $responseData)
    {
        $this->title = $title;
        $this->setResponseData($responseData);
    }


    protected function setResponseData(array $responseData): void
    {
        if (!Arr::exists($responseData, 'id')) throw WrapperException::instance("invalid json-array: no id provided");
        $this->responseData = $responseData;
        $this->fillFromRaw();
    }

    private function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillType();
        $this->fillContent();
    }

    private function fillType(): void
    {
        $this->type = $this->responseData['type'];
    }

    private function fillContent(): void
    {
        $this->rawContent = $this->responseData[$this->getType()];
    }

    public function getTitle():string{
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRawContent()
    {
        return $this->rawContent;
    }
}
