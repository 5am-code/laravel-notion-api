<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Endpoints\Databases;
use FiveamCode\LaravelNotionApi\Entities\Database;
use Illuminate\Support\Collection;

/**
 * Class DatabaseBuilder.
 */
class DatabaseBuilder
{
    /**
     * @var array
     */
    private array $payload;

    /**
     * DatabaseBuilder constructor.
     * @param Databases $databasesEndpoint
     */
    public function __construct(private Databases $databasesEndpoint)
    {
        $this->payload = [
            'is_inline' => false,
            'parent' => [],
            'title' => [
                [
                    'text' => [
                        'content' => '',
                    ],
                ],
            ],
            'properties' => [],
        ];
    }

    /**
     * Creates database within given page.
     * 
     * @param string $pageId
     * @return Database
     */
    public function createInPage($pageId): Database
    {
        $this->payload['parent'] = [
            'type' => 'page_id',
            'page_id' => $pageId,
        ];

        if ($this->payload['properties'] === []) {
            $this->addTitle();
        }

        return $this->databasesEndpoint->create($this->payload());
    }

    /**
     * Sets the title for the database creation.
     * 
     * @param string $title
     * @return DatabaseBuilder
     */
    public function title(string $title): DatabaseBuilder
    {
        $this->payload['title'] = [
            [
                'text' => [
                    'content' => $title,
                ],
            ],
        ];

        return $this;
    }

    /**
     * Sets the description for the database creation.
     * 
     * @param string $description
     * @return DatabaseBuilder
     */
    public function description(string $description): DatabaseBuilder
    {
        $this->payload['description'] = [
            [
                'text' => [
                    'content' => $description,
                ],
            ],
        ];

        return $this;
    }

    /**
     * Sets the created database as inline (currently not supported).
     * @todo increase Notion API Version, to make this work
     * 
     * @return DatabaseBuilder
     */
    public function inline(): DatabaseBuilder
    {
        $this->payload['is_inline'] = true;

        return $this;
    }

    /**
     * Sets the icon for the database creation.
     * 
     * @param string $icon
     * @return DatabaseBuilder
     */
    public function iconEmoji(string $icon): DatabaseBuilder
    {
        $this->payload['icon'] = [
            'type' => 'emoji',
            'emoji' => $icon,
        ];

        return $this;
    }

    /**
     * Sets the icon for the database creation.
     * 
     * @param string $url
     * @return DatabaseBuilder
     */
    public function iconExternal(string $url): DatabaseBuilder
    {
        $this->payload['icon'] = [
            'type' => 'external',
            'external' => [
                'url' => $url,
            ],
        ];

        return $this;
    }

    /**
     * Sets the cover for the database creation.
     * 
     * @param string $url
     * @return DatabaseBuilder
     */
    public function coverExternal(string $url): DatabaseBuilder
    {
        $this->payload['cover'] = [
            'type' => 'external',
            'external' => [
                'url' => $url,
            ],
        ];

        return $this;
    }

    /**
     * Adds the property `title` database creation.
     * 
     * @param string $name
     * @return DatabaseBuilder
     */
    public function addTitle(string $name = 'Name')
    {
        $this->add(PropertyBuilder::title($name));

        return $this;
    }

    /**
     * Adds one or multiple properties to the database creation.
     * 
     * @param PropertyBuilder|Collection|DatabaseSchemeBuilder $properties
     * @return DatabaseBuilder
     */
    public function add(PropertyBuilder|Collection|DatabaseSchemeBuilder $properties): DatabaseBuilder
    {
        if ($properties instanceof PropertyBuilder) {
            $properties = collect([$properties]);
        }

        if ($properties instanceof DatabaseSchemeBuilder) {
            $properties = $properties->getProperties();
        }

        $properties->each(function (PropertyBuilder $property) {
            $this->payload['properties'][$property->getName()] = $property->payload();
        });

        return $this;
    }

    /**
     * Adds multiple properties to the database creation, similar to a Laravel migration.
     * 
     * @param callable $callback
     * @return DatabaseBuilder
     */
    public function scheme(callable $callback): DatabaseBuilder
    {
        $builder = new DatabaseSchemeBuilder();
        $callback($builder);

        return $this->add($builder);
    }

    /**
     * Adds a raw property to the database creation.
     * 
     * @param string $title
     * @param string $propertyType
     * @param array|null $content
     * @return DatabaseBuilder
     */
    public function addRaw(string $title, string $propertyType, array $content = null): DatabaseBuilder
    {
        $this->payload['properties'][$title] = [];
        $this->payload['properties'][$title][$propertyType] = $content ?? new \stdClass();

        return $this;
    }

    /**
     * Returns the payload for the database creation.
     * 
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
