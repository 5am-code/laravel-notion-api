<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Endpoints\Databases;
use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use Illuminate\Support\Collection;

/**
 * Class DatabaseBuilder.
 */
class DatabaseBuilder
{
    private array $payload;

    public function __construct(private Databases $databasesEndpoint)
    {
        $this->payload = [
            'is_inline' => false,
            'parent' => [],
            'title' => [
                [
                    'text' => [
                        'content' => ''
                    ]
                ]
            ],
            'properties' => [],
        ];
    }

    public function createInPage($pageId): Database
    {
        $this->payload['parent'] = [
            'type' => 'page_id',
            'page_id' => $pageId
        ];

        if ($this->payload['properties'] === []) {
            $this->addTitle();
        }

        return $this->databasesEndpoint->create($this->payload());
    }

    public function title($title): DatabaseBuilder
    {
        $this->payload['title'] = [
            [
                'text' => [
                    'content' => $title
                ]
            ]
        ];
        return $this;
    }

    public function inline(): DatabaseBuilder
    {
        $this->payload['is_inline'] = true;
        return $this;
    }

    public function addTitle($name = 'Name')
    {
        $this->add(PropertyBuilder::title($name));
        return $this;
    }

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

    public function scheme(callable $callback): DatabaseBuilder
    {
        $builder = new DatabaseSchemeBuilder();
        $callback($builder);
        return $this->add($builder);
    }

    public function addRaw(string $title, string $propertyType, array $content = null): DatabaseBuilder
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