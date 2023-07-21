<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Arr;

/**
 * Class Property.
 */
class Property extends Entity
{
    const TITLE = 'title';
    const RICH_TEXT = 'rich_text';
    const NUMBER = 'number';
    const STATUS = 'status';
    const SELECT = 'select';
    const MULTI_SELECT = 'multi_select';
    const DATE = 'date';
    const PEOPLE = 'people';
    const FILES = 'files';
    const CHECKBOX = 'checkbox';
    const URL = 'url';
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phone_number';
    const FORMULA = 'formula';
    const RELATION = 'relation';
    const ROLLUP = 'rollup';
    const CREATED_TIME = 'created_time';
    const CREATED_BY = 'created_by';
    const LAST_EDITED_TIME = 'last_edited_time';
    const LAST_EDITED_BY = 'last_edited_by';

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
     *
     * @param  string|null  $title
     * @param  array  $responseData
     *
     * @throws HandlingException
     */
    public function __construct(string $title = null)
    {
        if ($title !== null) {
            $this->title = $title;
        }
    }

    /**
     * @param  array  $responseData
     *
     * @throws HandlingException
     */
    protected function setResponseData(array $responseData): void
    {
        if (! Arr::exists($responseData, 'id')) {
            throw HandlingException::instance('invalid json-array: no id provided');
        }
        $this->responseData = $responseData;
        $this->fillFromRaw();
    }

    protected function fillFromRaw(): void
    {
        parent::fillEssentials();
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     */
    public function setTitle(string $title): void
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
        if ($this->content == null) {
            return '';
        }

        if (is_scalar($this->content)) {
            return $this->content;
        }

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
     * @param  string  $propertyKey
     * @param  $rawContent
     * @return Property
     *
     * @throws HandlingException
     */
    public static function fromResponse(?string $propertyKey, $rawContent): Property
    {
        $propertyClass = self::mapTypeToClass($rawContent['type']);
        $property = new $propertyClass($propertyKey);

        $property->setResponseData($rawContent);

        return $property;
    }

    /**
     * Maps the type of a property to the corresponding package class by converting the type name.
     *
     * @param  string  $type
     * @return string
     */
    private static function mapTypeToClass(string $type): string
    {
        switch ($type) {
            case 'multi_select':
            case 'select':
            case 'created_by':
            case 'title':
            case 'number':
            case 'people':
            case 'checkbox':
            case 'date':
            case 'email':
            case 'phone_number':
            case 'url':
            case 'last_edited_by':
            case 'created_time':
            case 'last_edited_time':
            case 'files':
            case 'formula':
            case 'rollup':
            case 'relation':
                $class = str_replace('_', '', ucwords($type, '_'));

                return 'FiveamCode\\LaravelNotionApi\\Entities\\Properties\\'.$class;
            case 'text':
            case 'rich_text':
                // TODO: Depending on the Notion API version.
                return Text::class;
            default:
                return Property::class;
        }
    }
}
