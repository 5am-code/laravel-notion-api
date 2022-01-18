<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
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
     * @var Collection
     */
    private Collection $filter;

    /**
     * @var Collection
     */
    private Collection $sorts;

    /**
     * Database constructor.
     *
     * @param  string  $databaseId
     * @param  Notion  $notion
     *
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\LaravelNotionAPIException
     */
    public function __construct(string $databaseId, Notion $notion)
    {
        $this->databaseId = $databaseId;

        $this->sorts = new Collection();
        $this->filter = new Collection();

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

        if ($this->filter->isNotEmpty()) {
            $postData['filter']['or'] = Filter::filterQuery($this->filter);
        } // TODO Compound filters!

        if ($this->startCursor !== null) {
            $postData['start_cursor'] = (string) $this->startCursor;
        }

        if ($this->pageSize !== null) {
            $postData['page_size'] = $this->pageSize;
        }

        $response = $this
            ->post(
                $this->url(Endpoint::DATABASES."/{$this->databaseId}/query"),
                $postData
            )
            ->json();

        return new PageCollection($response);
    }

    /**
     * @param  Collection  $filter
     * @return $this
     */
    public function filterBy(Collection $filter): Database
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param  Collection  $sorts
     * @return $this
     */
    public function sortBy(Collection $sorts): Database
    {
        $this->sorts = $sorts;

        return $this;
    }
}
