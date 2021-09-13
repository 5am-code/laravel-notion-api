<?php

namespace FiveamCode\LaravelNotionApi\Query;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Collection;

/**
 * Class Filter
 * @package FiveamCode\LaravelNotionApi\Query
 */
class Filter extends QueryHelper
{
    /**
     * @var string|null
     */
    private ?string $filterType;
    /**
     * @var array|null
     */
    private ?array $filterConditions;
    /**
     * @var array|null
     */
    private ?array $filterDefinition;


    /**
     * Filter constructor.
     * @param string $property
     * @param string|null $filterType
     * @param array|null $filterConditions
     * @param array|null $filterDefinition
     */
    public function __construct(
        string $property,
        string $filterType = null,
        array  $filterConditions = null,
        array  $filterDefinition = null
    )
    {
        parent::__construct();

        $this->property = $property;
        $this->filterType = $filterType;
        $this->filterConditions = $filterConditions;
        $this->filterDefinition = $filterDefinition;
    }

    /**
     * Returns a text filter instance.
     *
     * @see https://developers.notion.com/reference/post-database-query#text-filter-condition
     *
     * @param string $property
     * @param array $filterConditions
     * @return Filter
     */
    public static function textFilter(string $property, array $filterConditions): Filter
    {
        return new Filter($property, 'text', $filterConditions);
    }

    /**
     * This method allows you to define every filter that is offered
     * by Notion but not implemented in this package yet. Provide the
     * filter definition as an array like explained in the Notion docs.
     * Use with caution; this method will be removed in the future and
     * is marked as deprecated from the start!
     *
     * @see https://developers.notion.com/reference/post-database-query#post-database-query-filter
     *
     * @param string $property
     * @param array $filterDefinition
     *
     * @return Filter
     * @deprecated
     */
    public static function rawFilter(string $property, array $filterDefinition): Filter
    {
        return new Filter($property, null, null, $filterDefinition);
    }

    /**
     * @return array
     * @throws HandlingException
     */
    public function toArray(): array
    {
        if ($this->filterDefinition !== null && $this->filterType === null && $this->filterConditions === null) {
            return array_merge(
                ['property' => $this->property],
                $this->filterDefinition
            );
        } elseif ($this->filterType !== null && $this->filterConditions !== null && $this->filterDefinition === null) {
            return [
                'property' => $this->property,
                $this->filterType => $this->filterConditions
            ];
        } else
            throw HandlingException::instance('Invalid filter definition.', ['invalidFilter' => $this]);

    }


    /**
     * @param Collection $filter
     * @return array
     * @throws HandlingException
     */
    public static function filterQuery(Collection $filter): array
    {

        $queryFilter = new Collection();

        $filter->each(function (Filter $filter) use ($queryFilter) {
            $queryFilter->add($filter->toArray());
        });

        return $queryFilter->toArray();

    }


}