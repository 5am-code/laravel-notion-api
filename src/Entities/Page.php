<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Properties\Checkbox;
use FiveamCode\LaravelNotionApi\Entities\Properties\Date;
use FiveamCode\LaravelNotionApi\Entities\Properties\Email;
use FiveamCode\LaravelNotionApi\Entities\Properties\MultiSelect;
use FiveamCode\LaravelNotionApi\Entities\Properties\Number;
use FiveamCode\LaravelNotionApi\Entities\Properties\People;
use FiveamCode\LaravelNotionApi\Entities\Properties\PhoneNumber;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Entities\Properties\Relation;
use FiveamCode\LaravelNotionApi\Entities\Properties\Select;
use FiveamCode\LaravelNotionApi\Entities\Properties\Text;
use FiveamCode\LaravelNotionApi\Entities\Properties\Title;
use FiveamCode\LaravelNotionApi\Entities\Properties\Url;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Page
 * @package FiveamCode\LaravelNotionApi\Entities
 */
class Page extends Entity
{
    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $objectType = '';

    /**
     * @var array
     */
    protected array $rawProperties = [];

    /**
     * @var array
     */
    protected array $propertyMap = [];

    /**
     * @var array
     */
    protected array $propertyKeys = [];

    /**
     * @var Collection
     */
    protected Collection $properties;

    /**
     * @var DateTime
     */
    protected DateTime $createdTime;

    /**
     * @var DateTime
     */
    protected DateTime $lastEditedTime;


    /**
     * Page constructor.
     * @param array|null $responseData
     * @throws HandlingException
     * @throws NotionException
     */
    public function __construct(array $responseData = null)
    {
        $this->properties = new Collection();
        parent::__construct($responseData);
    }


    /**
     * @param array $responseData
     * @throws HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\NotionException
     */
    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'page') throw HandlingException::instance('invalid json-array: the given object is not a page');
        $this->fillFromRaw();
    }

    /**
     *
     */
    private function fillFromRaw(): void
    {
        $this->fillId();
        $this->fillObjectType();
        $this->fillProperties();
        $this->fillTitle(); // This has to be called after fillProperties(), since title is provided by properties
        $this->fillCreatedTime();
        $this->fillLastEditedTime();
    }

    /**
     *
     */
    private function fillObjectType(): void
    {
        if (Arr::exists($this->responseData, 'object')) {
            $this->objectType = $this->responseData['object'];
        }
    }

    /**
     * @throws HandlingException
     */
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

    /**
     *
     */
    private function fillTitle(): void
    {
        $titleProperty = $this->properties->filter(function ($property) {
            return $property->getType() == 'title';
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

    /**
     * @param $propertyTitle
     * @param $property
     */
    public function set(string $propertyTitle, Property $property): void
    {
        $property->setTitle($propertyTitle);
        $this->properties->add($property);

        if ($property instanceof Title) {
            $this->title = $property->getPlainText();
        }
    }

    /**
     * @param $propertyTitle
     * @param $number
     */
    public function setNumber(string $propertyTitle, float $number): void
    {
        $this->set($propertyTitle, Number::value($number));
    }

    /**
     * @param $propertyTitle
     * @param $text
     */
    public function setTitle(string $propertyTitle, string $text): void
    {
        $this->set($propertyTitle, Title::value($text));
    }

    /**
     * @param $propertyTitle
     * @param $text
     */
    public function setText(string $propertyTitle, string $text): void
    {
        $this->set($propertyTitle, Text::value($text));
    }

    /**
     * @param $propertyTitle
     * @param $name
     */
    public function setSelect(string $propertyTitle, string $name): void
    {
        $this->set($propertyTitle, Select::value($name));
    }

    /**
     * @param $propertyTitle
     * @param $url
     */
    public function setUrl(string $propertyTitle, string $url): void
    {
        $this->set($propertyTitle, Url::value($url));
    }

    /**
     * @param $propertyTitle
     * @param $phoneNumber
     */
    public function setPhoneNumber(string $propertyTitle, string $phoneNumber): void
    {
        $this->set($propertyTitle, PhoneNumber::value($phoneNumber));
    }

    /**
     * @param $propertyTitle
     * @param $email
     */
    public function setEmail(string $propertyTitle, string $email): void
    {
        $this->set($propertyTitle, Email::value($email));
    }

    /**
     * @param $propertyTitle
     * @param $names
     */
    public function setMultiSelect(string $propertyTitle, array $names): void
    {
        $this->set($propertyTitle, MultiSelect::value($names));
    }

    /**
     * @param $propertyTitle
     * @param $checked
     */
    public function setCheckbox(string $propertyTitle, bool $checked): void
    {
        $this->set($propertyTitle, Checkbox::value($checked));
    }


    /**
     * @param $propertyTitle
     * @param $start
     * @param $end
     */
    public function setDate(string $propertyTitle, ?DateTime $start, ?DateTime $end = null): void
    {
        $this->set($propertyTitle, Date::value($start, $end));
    }

    /**
     * @param $propertyTitle
     * @param $relationIds
     */
    public function setRelation(string $propertyTitle, array $relationIds): void
    {
        $this->set($propertyTitle, Relation::value($relationIds));
    }

    /**
     * @param $propertyTitle
     * @param $userIds
     */
    public function setPeople(string $propertyTitle, array $userIds): void
    {
        $this->set($propertyTitle, People::value($userIds));
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Collection
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    /**
     * @param string $propertyKey
     * @return Property|null
     */
    public function getProperty(string $propertyKey): ?Property
    {
        if (!isset($this->propertyMap[$propertyKey])) {
            return null;
        }
        return $this->propertyMap[$propertyKey];
    }

    /**
     * @return string
     */
    public function getObjectType(): string
    {
        return $this->objectType;
    }

    /**
     * @return array
     */
    public function getRawProperties(): array
    {
        return $this->rawProperties;
    }

    /**
     * @return array
     */
    public function getPropertyKeys(): array
    {
        return $this->propertyKeys;
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
