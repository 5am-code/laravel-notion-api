<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Arr;

class Property extends Entity
{
    protected string $title;
    protected string $type;
    protected $rawContent;
    protected $content;

    public function __construct(string $title, array $responseData)
    {
        $this->title = $title;
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
        $this->fillType();
        $this->fillContent();
    }

    private function fillType(): void
    {
        if (Arr::exists($this->responseData, 'type')) {
            $this->type = $this->responseData['type'];
        }
    }

    private function fillContent(): void
    {
        if (Arr::exists($this->responseData, $this->getType())) {
            $this->rawContent = $this->responseData[$this->getType()];
            $this->content = $this->rawContent;
        }
    }

    public function getTitle(): string
    {
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

    public function getContent()
    {
        return $this->rawContent;
    }

    public static function fromResponse($propertyKey, $rawContent): Property
    {
        if ($rawContent['type'] == 'multi_select') {
            return new MultiSelect($propertyKey, $rawContent);
        } else if ($rawContent['type'] == 'select') {
            return new Select($propertyKey, $rawContent);
        } else if ($rawContent['type'] == 'text') {
            return new Text($propertyKey, $rawContent);
        } else if ($rawContent['type'] == 'created_by') {
            return new CreatedBy($propertyKey, $rawContent);
        } else if ($rawContent['type'] == 'title') {
            return new Title($propertyKey, $rawContent);
        } else if ($rawContent['type'] == 'number') {
            return new Number($propertyKey, $rawContent);
        }


        return new Property($propertyKey, $rawContent);
    }
}
