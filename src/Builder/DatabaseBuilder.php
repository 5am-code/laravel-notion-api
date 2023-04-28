<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Endpoints\Databases;
use FiveamCode\LaravelNotionApi\Entities\Database;

/**
 * Class DatabaseBuilder.
 */
class DatabaseBuilder
{
    private array $payload;

    public function __construct(private Databases $databasesEndpoint)
    {
        $this->payload = [
            'title' => [
                [
                    'text' => [
                        'content' => '',
                    ],
                ],
            ],
            'parent' => [],
            'properties' => [],
        ];
    }

    public function createInPage($pageId): Database
    {
        $this->payload['parent'] = [
            'page_id' => $pageId,
        ];

        if ($this->payload['properties'] === []) {
            $this->addTitleProperty();
        }

        return $this->databasesEndpoint->create($this->payload());
    }

    public function title($title): DatabaseBuilder
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

    public function inline(): DatabaseBuilder
    {
        $this->payload['is_inline'] = true;

        return $this;
    }

    public function addTitleProperty($name = 'Name')
    {
        $this->addProperty($name, PropertyBuilder::title());

        return $this;
    }

    public function addProperty(string $title, string|PropertyBuilder $property): DatabaseBuilder
    {
        if (is_string($property)) {
            $property = PropertyBuilder::plain($property);
        }

        $this->payload['properties'][$title] = $property->payload();

        return $this;
    }

    public function addRawProperty(string $title, string $propertyType, array $content = null): DatabaseBuilder
    {
        $this->payload['properties'][$title] = [];
        $this->payload['properties'][$title][$propertyType] = $content ?? new \stdClass();

        return $this;
    }

    public function payload(): array
    {
        return $this->payload;
    }
}
