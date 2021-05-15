<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Page extends Entity
{

    protected array $rawProperties = [];
    protected Collection $propertyCollection;
    protected DateTime $createdTime;
    protected DateTime $lastEditedTime;


    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'page') throw WrapperException::instance("invalid json-array: the given object is not a page");
        $this->fillFromRaw();
    }

    private function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillProperties();
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
    }

    private function fillProperties(): void
    {
        if (Arr::exists($this->responseData, 'properties')) {
            $this->rawProperties = $this->responseData['properties'];
            $this->propertyCollection = new Collection();
            foreach (array_keys($this->rawProperties) as $propertyKey) {
                $this->propertyCollection->add(new Property($propertyKey, $this->rawProperties[$propertyKey]));
            }
        }
    }


    public function getProperties(): Collection
    {
        return $this->propertyCollection;
    }

    public function getProperty(string $propertyName): Property
    {
        $property = $this->propertyCollection->filter(function($property) use($propertyName) {
            return $property->getTitle() == $propertyName;
        })->first();

        return $property;
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
