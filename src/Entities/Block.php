<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;

class Block extends Entity
{
    protected string $type;
    protected bool $hasChildren;
    protected array $rawContent;
    protected DateTime $createdTime;
    protected DateTime $lastEditedTime;

    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'block') throw WrapperException::instance("invalid json-array: the given object is not a block");

        $this->fillFromRaw();
    }

    private function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillType();
        $this->fillContent();
        $this->fillHasChildren();
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
    }

    private function fillType(): void
    {
        if (Arr::exists($this->responseData, 'type')) {
            $this->type = $this->responseData['type'];
        }
    }

    private function fillContent(): void
    {
        if (Arr::exists($this->responseData, $this->type)) {
            $this->rawContent = $this->responseData[$this->type];
        }
    }

    private function fillHasChildren(): void
    {
        if (Arr::exists($this->responseData, 'has_children')) {
            $this->hasChildren = $this->responseData['has_children'];
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRawContent(): array
    {
        return $this->rawContent;
    }

    public function hasChildren(): bool
    {
        return $this->hasChildren;
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
