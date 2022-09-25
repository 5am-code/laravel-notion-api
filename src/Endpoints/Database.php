<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Collections\EntityCollection;
use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\FilterBag;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use Illuminate\Support\Collection;

/**
 * Class Database.
 */
class Database extends Endpoint
{
    /**
     * @var string
     */
    private string $databaseId;

    /**
     * @var Filter|null
     */
    private ?Filter $filter = null; // TODO breaking change as well

    private $filterBag;

    private array $filterData = [];

    /**
     * @var Collection
     */
    private Collection $sorts;

    /**
     * Database constructor.
     *
     * @param string $databaseId
     * @param Notion $notion
     *
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\LaravelNotionAPIException
     */
    public function __construct(string $databaseId, Notion $notion)
    {
        $this->databaseId = $databaseId;

        $this->sorts = new Collection();

        parent::__construct($notion);
    }

    /**
     * @return PageCollection
     *
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\NotionException
     */
    public function query(): PageCollection
    {
        $postData = [];

        if ($this->sorts->isNotEmpty()) {
            $postData['sorts'] = Sorting::sortQuery($this->sorts);
        }

        if($this->filter !== null && !is_null($this->filterBag)) {
            throw new HandlingException("Please provide either a filter bag or a single filter.");
        }
        elseif ($this->filter !== null || !is_null($this->filterBag)) {
            $postData['filter'] = $this->filterData;
        }

        if ($this->startCursor !== null) {
            $postData['start_cursor'] = $this->startCursor->__toString();
        }

        if ($this->pageSize !== null) {
            $postData['page_size'] = $this->pageSize;
        }

        $response = $this
            ->post(
                $this->url(Endpoint::DATABASES . "/{$this->databaseId}/query"),
                $postData
            )
            ->json();

        return new PageCollection($response);
    }

    /**
     * @param $filter
     * @return Database $this
     * @throws HandlingException
     * @todo As soon as this package drops PHP 7.4 support, we can use union types here (FilterBag and Filter)
     */
    public function filterBy($filter): Database // TODO that's a breaking change
    {
        $this->checkFilterType($filter);

        if($filter instanceof FilterBag) {
            return $this->filterByBag($filter);
        }
        if($filter instanceof Filter) {
            return $this->filterBySingleFilter($filter);
        }

        return $this;
    }

    public function filterBySingleFilter(Filter $filter): Database
    {
        $this->filter = $filter;
        $this->filterData = ["or" => [$filter->toQuery()]];

        return $this;
    }

    /**
     * @param FilterBag $filterBag
     * @return $this
     */
    public function filterByBag(FilterBag $filterBag): Database
    {
        $this->filterBag = $filterBag;
        $this->filterData = $filterBag->toQuery();

        return $this;
    }

    /**
     * @param Collection $sorts
     * @return $this
     */
    public function sortBy(Collection $sorts): Database
    {
        $this->sorts = $sorts;

        return $this;
    }

    /**
     * @param  EntityCollection  $entityCollection
     * @return $this
     */
    public function offsetByResponse(EntityCollection $entityCollection): Database
    {
        $this->offset($entityCollection->nextCursor());

        return $this;
    }

    private function checkFilterType($filter): void
    {
        if (!($filter instanceof Filter || $filter instanceof FilterBag)) {
            throw new HandlingException("Please provide either a filter bag or a single filter.");
        }
    }
}
