<?php

namespace FiveamCode\LaravelNotionApi\Entities\PropertyItems;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SelectItem extends Entity
{
    private string $color;
    private string $name;

    public function __construct(array $responseData)
    {
        $this->setResponseData($responseData);
    }

    protected function setResponseData(array $responseData): void
    {
        if (!Arr::exists($responseData, 'id')) throw HandlingException::instance("invalid json-array: no id provided");
        $this->responseData = $responseData;
        $this->fillFromRaw();
    }

    protected function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillName();
        $this->fillColor();
    }

    
    protected function fillName():void{
        if (Arr::exists($this->responseData, 'name')) {
            $this->name = $this->responseData['name'];
        }
    }

    protected function fillColor():void{
        if (Arr::exists($this->responseData, 'color')) {
            $this->color = $this->responseData['color'];
        }
    }



    public function getColor(): string
    {
        return $this->color;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
