<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\Filter;
use FiveamCode\LaravelNotionApi\Query\Sorting;

class Database extends Endpoint
{
    private string $databaseId;

    private Collection $filter;
    private Collection $sorts;


    public function __construct(string $databaseId, Notion $notion)
    {
        $this->databaseId = $databaseId;

        $this->sorts = new Collection();
        $this->filter = new Collection();

        parent::__construct($notion);
    }

    public function query(): PageCollection
    {
        $postData = [];

        if ($this->sorts->isNotEmpty())
            $postData["sorts"] = Sorting::sortQuery($this->sorts);

        if ($this->filter->isNotEmpty())
            $postData["filter"]["or"] = Filter::filterQuery($this->filter); // TODO Compound filters!

        if ($this->startCursor !== null)
            $postData["start_cursor"] = $this->startCursor;

        if ($this->pageSize !== null)
            $postData["page_size"] = $this->pageSize;


        $response = $this
            ->post(
                $this->url(Endpoint::DATABASES . "/{$this->databaseId}/query"),
                $postData
            )
            ->json();

        return new PageCollection($response);
    }

    public function filterBy(Collection $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function sortBy(Collection $sorts)
    {
        $this->sorts = $sorts;
        return $this;
    }
}
