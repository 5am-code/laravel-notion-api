<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;


class Database extends Entity
{
    protected string $title = "";
    protected string $objectType = "";
    protected array $rawTitle = [];
    protected array $rawProperties = [];
    protected DateTime $createdTime;
    protected DateTime $lastEditedTime;


    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'database')
            throw HandlingException::instance("invalid json-array: the given object is not a database");
        $this->fillFromRaw();
    }


    private function fillFromRaw()
    {
        $this->fillId();
        $this->fillTitle();
        $this->fillObjectType();
        $this->fillProperties();
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
    }

    private function fillTitle(): void
    {
        if (Arr::exists($this->responseData, 'title') && is_array($this->responseData['title'])) {
            $this->title = Arr::first($this->responseData['title'], null, ['plain_text' => ''])['plain_text'];
            $this->rawTitle = $this->responseData['title'];
        }
    }

    private function fillObjectType(): void
    {
        if (Arr::exists($this->responseData, 'object')) {
            $this->objectType = $this->responseData['object'];
        }
    }

    private function fillProperties(): void
    {
        if (Arr::exists($this->responseData, 'properties')) {
            $this->rawProperties = $this->responseData['properties'];
        }
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }


    public function getTitle(): string
    {
        return $this->title;
    }

    public function getProperties()
    {
        //TODO: return collection of property-entities (id, type, title)
        throw new \Exception("not implemented yet");
    }

    public function getRawTitle(): array
    {
        return $this->rawTitle;
    }

    public function getRawProperties(): array
    {
        return $this->rawProperties;
    }

    public function getPropertyNames(): array
    {
        return array_keys($this->rawProperties);
    }

    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }

    public function getLastEditedTime(): DateTime
    {
        return $this->lastEditedTime;
    }
}
