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

    protected function setRaw(array $raw): void
    {
        parent::setRaw($raw);
        if ($raw['object'] !== 'database') throw WrapperException::instance("invalid json-array: the given object is not a database");

        if (Arr::exists($raw, 'title') && is_array($raw['title'])) {
            $this->title = Arr::first($raw['title'], null, ['plain_text' => ''])['plain_text'];
            $this->rawTitle = $raw['title'];
        }

        if (Arr::exists($raw, 'properties')) {
            $this->rawProperties = $raw['properties'];
        }

        if (Arr::exists($raw, 'created_time')) {
            $this->createdTime = new Carbon($raw['created_time']);
        }

        if (Arr::exists($raw, 'last_edited_time')) {
            $this->lastEditedTime = new Carbon($raw['last_edited_time']);
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
