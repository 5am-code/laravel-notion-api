<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use Carbon\Carbon;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;


class Database extends Entity
{
    private string $title = "";
    private array $rawTitle = [];
    private array $rawProperties = [];
    private DateTime $createdTime;
    private DateTime $lastEditedTime;


    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'database') throw WrapperException::instance("invalid json-array: the given object is not a database");
        $this->fillFromRaw();
    }


    private function fillFromRaw()
    {
        $this->fillId();
        $this->fillTitle();
        $this->fillProperties();
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
    }

    private function fillTitle()
    {
        if (Arr::exists($this->responseData, 'title') && is_array($this->responseData['title'])) {
            $this->title = Arr::first($this->responseData['title'], null, ['plain_text' => ''])['plain_text'];
            $this->rawTitle = $this->responseData['title'];
        }
    }

    private function fillProperties()
    {
        if (Arr::exists($this->responseData, 'properties')) {
            $this->rawProperties = $this->responseData['properties'];
        }
    }

    private function fillCreatedTime()
    {
        if (Arr::exists($this->responseData, 'created_time')) {
            $this->createdTime = new Carbon($this->responseData['created_time']);
        }
    }

    private function fillLastEditedTime()
    {
        if (Arr::exists($this->responseData, 'last_edited_time')) {
            $this->lastEditedTime = new Carbon($this->responseData['last_edited_time']);
        }
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
