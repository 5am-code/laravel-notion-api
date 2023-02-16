<?php

namespace FiveamCode\LaravelNotionApi\Models;

use FiveamCode\LaravelNotionApi\Endpoints\Database;
use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\FilterBag;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class NotionQueryBuilder
{
    /**
     * @var ?StartCursor
     */
    private static ?StartCursor $nextCursor = null;

    private $modelClass = null;

    public ?Database $endpoint = null;
    public ?FilterBag $filters = null;
    public ?Collection $sortings = null;

    private $localConvertPropsToText = false;

    public function __construct($class)
    {
        $this->modelClass = $class;
        $this->endpoint = $this->modelClass::notionInstance()
            ->database($this->modelClass::$databaseId);
    }

    public function pluck($value, $key = null): Collection
    {
        $pageCollection = $this->internalQuery();

        return $pageCollection->pluck('props.'.$value, $key !== null ? 'props.'.$key : null);
    }

    private function queryToNotion(int $limit = 100): PageCollection
    {
        $notionQuery = $this->modelClass::notionInstance()
            ->database($this->modelClass::databaseId())
            ->limit($limit);

        if ($this->filters) {
            $notionQuery->filterBy($this->filters);
        }

        if ($this->modelClass::$offset) {
            $notionQuery->offset(new StartCursor($this->modelClass::$offset));
        }

        return $notionQuery->query();
    }

    /**
     * @return Collection<static>
     */
    private function internalQuery(int $limit = 100): Collection
    {
        if ($this->modelClass::$cursorHistory === null) {
            $this->modelClass::$cursorHistory = new Collection();
        }

        if ($this->modelClass::$cacheDurationInSeconds === 0) {
            $queryResponse = $this->queryToNotion($limit);
        } else {
            $queryResponse = Cache::remember(static::cacheKey(), $this->modelClass::$cacheDurationInSeconds, function () use ($limit) {
                return $this->queryToNotion($limit);
            });
        }

        $instances = collect();

        foreach ($queryResponse->asCollection() as $pageItem) {
            $instance = $this->modelClass::createInstance($this->modelClass::$databaseId);
            $instance->page = $pageItem;

            foreach ($pageItem->getProperties() as $propertyItem) {
                $propertyContent = $propertyItem->getContent();
                if ($this->modelClass::$convertPropsToText || $this->localConvertPropsToText) {
                    $propertyContent = $propertyItem->asText();
                }
                $instance->{Str::slug($propertyItem->getTitle(), '_')} = $propertyContent;
            }

            $instances->add($instance);
        }

        self::$nextCursor = $queryResponse->nextCursor();
        $this->modelClass::$cursorHistory->add(self::$nextCursor);

        return $instances;
    }

    /**
     * @return Collection<static>
     */
    public function get()
    {
        return $this->internalQuery();
    }

    /**
     * @return static
     */
    public function first()
    {
        return $this->get()->first();
    }

    /**
     * @return string
     */
    public function getAsJson()
    {
        return $this->internalQuery()->asJson();
    }

    public function getAll()
    {
        throw new \Exception('Not implemented yet');
    }

    public function getAllAsJson()
    {
        throw new \Exception('Not implemented yet');
    }

    public function propsToText()
    {
        $this->localConvertPropsToText = true;

        return $this;
    }

    public function offset($offset)
    {
        $this->endpoint->offset($offset);

        return $this;
    }

    public function limit($offset)
    {
        $this->endpoint->limit($offset);

        return $this;
    }

    public function orderBy($property, $direction = 'asc')
    {
        if ($this->sortings == null) {
            $this->sortings = collect();
        }

        if ($direction == 'asc') {
            $direction = 'ascending';
        }
        if ($direction == 'desc') {
            $direction = 'descending';
        }

        $this->sortings
            ->add(Sorting::propertySort($property, $direction));

        return $this;
    }

    public function whereNull($property)
    {
        return $this->where($property, Operators::IS_EMPTY, null);
    }

    public function whereNotNull($property)
    {
        return $this->where($property, Operators::IS_NOT_EMPTY, null);
    }

    public function where($property, $operator, $value = null)
    {
        if ($this->filters == null) {
            $this->filters = new FilterBag();
        }

        if ($value == null) {
            $value = $operator;
            $operator = Operators::EQUALS;
        } else {
            switch (Str::lower($operator)) {
                case '=':
                    $operator = Operators::EQUALS;
                    break;
                case '!=':
                    $operator = Operators::DOES_NOT_EQUAL;
                    break;
                case '<':
                    $operator = Operators::LESS_THAN;
                    break;
                case '<=':
                    $operator = Operators::LESS_THAN_OR_EQUAL_TO;
                    break;
                case '>':
                    $operator = Operators::GREATER_THAN;
                    break;
                case '>=':
                    $operator = Operators::GREATER_THAN_OR_EQUAL_TO;
                    break;
                case 'contains':
                    $operator = Operators::CONTAINS;
                    break;
            }
        }

        if (Arr::has($this->modelClass::$propertyTitleMap, $property)) {
            $property = $this->modelClass::$propertyTitleMap[$property];
        }

        if (is_string($value)) {
            $this->filters->addFilter(
                Filter::textFilter($property, $operator, $value)
            );
        } elseif (is_numeric($value)) {
            $this->filters->addFilter(
                Filter::numberFilter($property, $operator, $value)
            );
        } else {
            $this->filters->addFilter(
                Filter::textFilter($property, $operator, $value)
            );
        }

        return $this;
    }

    public function paginate($pageSize = 100)
    {
        $this->endpoint->limit($pageSize);
        $offset = Request::get('cursor');

        if ($offset !== null) {
            $this->endpoint->offset(new StartCursor($offset));
        }

        $result = $this->internalQuery();

        return [
            'per_page' => $pageSize,
            'next_cursor' => $result->getRawNextCursor(),
            'next_page_url' => Request::fullUrlWithQuery(['cursor' => $result->getRawNextCursor()]),
            'from' => $result->asCollection()->first()->getId(),
            'to' => $result->asCollection()->last()->getId(),
            'data' => $result->asCollection(),
        ];
    }

    /**
     * @return string
     */
    private function cacheKey(): string
    {
        $postCacheKey = '';
        if ($this->nextCursor !== null) {
            $postCacheKey = '-'.$this->nextCursor->__toString();
        }

        return  $this->modelClass::$preCacheKey.$this->modelClass::$databaseId.$postCacheKey;
    }
}
