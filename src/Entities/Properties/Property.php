<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Property
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Property extends Entity
{
    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var
     */
    protected $rawContent;

    /**
     * @var
     */
    protected $content;

    /**
     * Property constructor.
     * @param string $title
     * @param array $responseData
     * @throws HandlingException
     */
    public function __construct(string $title, array $responseData)
    {
        $this->title = $title;
        $this->setResponseData($responseData);
    }


    /**
     * @param array $responseData
     * @throws HandlingException
     */
    protected function setResponseData(array $responseData): void
    {
        if (!Arr::exists($responseData, 'id')) throw HandlingException::instance("invalid json-array: no id provided");
        $this->responseData = $responseData;
        $this->fillFromRaw();
    }

    /**
     *
     */
    protected function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillType();
        $this->fillContent();
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
            $this->content = $this->rawContent;
        }
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getRawContent()
    {
        return $this->rawContent;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->rawContent;
    }

    /**
     * @param $propertyKey
     * @param $rawContent
     * @return Property
     * @throws HandlingException
     */
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
