<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Page extends Entity
{
    protected string $title = "";
    protected string $objectType = "";
    protected array $rawProperties = [];
    protected array $propertyMap = [];
    protected array $propertyKeys = [];
    protected Collection $properties;
    protected DateTime $createdTime;
    protected DateTime $lastEditedTime;


    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'page') throw HandlingException::instance("invalid json-array: the given object is not a page");
        $this->fillFromRaw();
    }

    private function fillFromRaw(): void
    {
        $this->fillId();    
        $this->fillObjectType();
        $this->fillProperties();
        $this->fillTitle(); //!Warning: call after 'fillProperties', since title is included within properties
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
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
            $this->properties = new Collection();
            foreach (array_keys($this->rawProperties) as $propertyKey) {
                $property = Property::fromResponse($propertyKey, $this->rawProperties[$propertyKey]);
                $this->properties->add($property);
                $this->propertyMap[$propertyKey] = $property;
            }
            $this->propertyKeys = array_keys($this->rawProperties);
        }
    }

    private function fillTitle(): void
    {
        $titleProperty = $this->properties->filter(function ($property) {
            return $property->getType() == "title";
        })->first();

        if ($titleProperty !== null) {
            $rawTitleProperty = $titleProperty->getRawContent();
            if (is_array($rawTitleProperty) && count($rawTitleProperty) >= 1) {
                if (Arr::exists($rawTitleProperty[0], 'plain_text')) {
                    $this->title = $rawTitleProperty[0]['plain_text'];
                }
            }
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function getProperty(string $propertyKey): ?Property
    {
        if(!isset($this->propertyMap[$propertyKey])){
            return null;
        }
        return $this->propertyMap[$propertyKey];
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getRawProperties(): array
    {
        return $this->rawProperties;
    }

    public function getPropertyKeys(): array
    {
        return $this->propertyKeys;
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
