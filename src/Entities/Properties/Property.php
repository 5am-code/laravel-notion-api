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
     * @var array
     */
    protected $rawContent;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * Property constructor.
     * @param string|null $title
     * @param array $responseData
     * @throws HandlingException
     */
    public function __construct(string $title = null)
    {
        if ($title != null) $this->title = $title;
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

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function asText(): string
    {
        if($this->content == null) return "";
        return json_encode($this->content);
    }

    /**
     * @return array
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
        $property = null;
        if ($rawContent['type'] == 'multi_select') {
            $property = new MultiSelect($propertyKey);
        } else if ($rawContent['type'] == 'select') {
            $property = new Select($propertyKey);
        } else if ($rawContent['type'] == 'text') {
            $property = new Text($propertyKey);
        } else if ($rawContent['type'] == 'created_by') {
            $property = new CreatedBy($propertyKey);
        } else if ($rawContent['type'] == 'title') {
            $property = new Title($propertyKey);
        } else if ($rawContent['type'] == 'number') {
            $property = new Number($propertyKey);
        } else if ($rawContent['type'] == 'people') {
            $property = new People($propertyKey);
        } else if ($rawContent['type'] == 'checkbox') {
            $property = new Checkbox($propertyKey);
        } else if ($rawContent['type'] == 'date') {
            $property = new Date($propertyKey);
        } else if ($rawContent['type'] == 'email') {
            $property = new Email($propertyKey);
        } else if ($rawContent['type'] == 'phone_number') {
            $property = new PhoneNumber($propertyKey);
        } else if ($rawContent['type'] == 'url') {
            $property = new Url($propertyKey);
        }else if ($rawContent['type'] == 'last_edited_by') {
            $property = new LastEditedBy($propertyKey);
        }else if ($rawContent['type'] == 'created_time') {
            $property = new CreatedTime($propertyKey);
        }else if ($rawContent['type'] == 'last_edited_time') {
            $property = new LastEditedTime($propertyKey);
        }else if ($rawContent['type'] == 'files') {
            $property = new Files($propertyKey);
        }else if ($rawContent['type'] == 'formula') {
            $property = new Formula($propertyKey);
        }else if ($rawContent['type'] == 'rollup') {
            $property = new Rollup($propertyKey);
        } else {
            $property = new Property($propertyKey);
        }

        $property->setResponseData($rawContent);
        return $property;
    }
}
