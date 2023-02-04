<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Properties\People;
use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Traits\TimestampableEntity;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Database.
 */
class Database extends Entity
{
    use TimestampableEntity;

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    private string $icon = '';

    /**
     * @var string
     */
    private string $iconType = '';

    /**
     * @var string
     */
    private string $cover = '';

    /**
     * @var string
     */
    private string $coverType = '';

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    protected string $objectType = '';

    /**
     * @var array
     */
    protected array $rawTitle = [];

    /**
     * @var array
     */
    protected array $rawProperties = [];

    /**
     * @var array
     */
    protected array $propertyKeys = [];

    /**
     * @var array
     */
    protected array $propertyMap = [];

    /**
     * @var Collection
     */
    protected Collection $properties;



    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        if ($responseData['object'] !== 'database') {
            throw HandlingException::instance('invalid json-array: the given object is not a database');
        }
        $this->fillFromRaw();
    }

    private function fillFromRaw()
    {
        $this->fillId();
        $this->fillIcon();
        $this->fillCover();
        $this->fillTitle();
        $this->fillObjectType();
        $this->fillProperties();
        $this->fillDatabaseUrl();
        $this->fillTimestampableProperties();
    }

    private function fillTitle(): void
    {
        if (Arr::exists($this->responseData, 'title') && is_array($this->responseData['title'])) {
            $this->title = Arr::first($this->responseData['title'], null, ['plain_text' => ''])['plain_text'];
            $this->rawTitle = $this->responseData['title'];
        }
    }

    private function fillDatabaseUrl(): void
    {
        if (Arr::exists($this->responseData, 'url')) {
            $this->url = $this->responseData['url'];
        }
    }

    private function fillIcon(): void
    {
        if (Arr::exists($this->responseData, 'icon') && $this->responseData['icon'] != null) {
            $this->iconType = $this->responseData['icon']['type'];
            if (Arr::exists($this->responseData['icon'], 'emoji')) {
                $this->icon = $this->responseData['icon']['emoji'];
            } elseif (Arr::exists($this->responseData['icon'], 'file')) {
                $this->icon = $this->responseData['icon']['file']['url'];
            } elseif (Arr::exists($this->responseData['icon'], 'external')) {
                $this->icon = $this->responseData['icon']['external']['url'];
            }
        }
    }

    private function fillCover(): void
    {
        if (Arr::exists($this->responseData, 'cover') && $this->responseData['cover'] != null) {
            $this->coverType = $this->responseData['cover']['type'];
            if (Arr::exists($this->responseData['cover'], 'file')) {
                $this->cover = $this->responseData['cover']['file']['url'];
            } elseif (Arr::exists($this->responseData['cover'], 'external')) {
                $this->cover = $this->responseData['cover']['external']['url'];
            }
        }
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
            $this->propertyKeys = array_keys($this->rawProperties);
            $this->properties = new Collection();

            foreach ($this->rawProperties as $propertyKey => $propertyContent) {
                $propertyObj = Property::fromResponse($propertyKey, $propertyContent);
                $this->properties->add($propertyObj);
                $this->propertyMap[$propertyKey] = $propertyObj;
            }
        }
    }

    /**
     * @param  string  $propertyKey
     * @return Property|null
     */
    public function getProperty(string $propertyKey): ?Property
    {
        if (! isset($this->propertyMap[$propertyKey])) {
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getIconType(): string
    {
        return $this->iconType;
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @return string
     */
    public function getCoverType(): string
    {
        return $this->coverType;
    }

    /**
     * @return Collection
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    /**
     * @return array
     */
    public function getRawTitle(): array
    {
        return $this->rawTitle;
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
}
