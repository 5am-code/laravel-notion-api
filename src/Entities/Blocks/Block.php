<?php

namespace FiveamCode\LaravelNotionApi\Entities\Blocks;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Block
 * @package FiveamCode\LaravelNotionApi\Entities\Blocks
 */
class Block extends Entity
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var bool
     */
    protected bool $hasChildren;

    /**
     * @var array
     */
    protected array $rawContent;

    /**
     * @var DateTime
     */
    protected DateTime $createdTime;

    /**
     * @var DateTime
     */
    protected DateTime $lastEditedTime;

    /**
     * @param array $responseData
     * @throws HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\NotionException
     */
    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'block') throw HandlingException::instance('invalid json-array: the given object is not a block');

        $this->fillFromRaw();
    }

    /**
     *
     */
    private function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillType();
        $this->fillContent();
        $this->fillHasChildren();
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
    }

    /**
     *
     */
    private function fillType(): void
    {
        if (Arr::exists($this->responseData, 'type')) {
            $this->type = $this->responseData['type'];
        }
    }

    /**
     *
     */
    private function fillContent(): void
    {
        if (Arr::exists($this->responseData, $this->getType())) {
            $this->rawContent = $this->responseData[$this->getType()];
        }
    }

    /**
     *
     */
    private function fillHasChildren(): void
    {
        if (Arr::exists($this->responseData, 'has_children')) {
            $this->hasChildren = $this->responseData['has_children'];
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getRawContent(): array
    {
        return $this->rawContent;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return $this->hasChildren;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }

    /**
     * @return DateTime
     */
    public function getLastEditedTime(): DateTime
    {
        return $this->lastEditedTime;
    }
}
