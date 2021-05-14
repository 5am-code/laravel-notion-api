<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\Filter;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;

class Database extends Endpoint
{
    private string $databaseId;

    private Collection $filter;
    private Collection $sorts;

    private ?StartCursor $startCursor = null;
    private ?int $pageSize = null;


    public function __construct(string $databaseId, Notion $notion)
    {
        $this->databaseId = $databaseId;

        $this->sorts = new Collection();
        $this->filter = new Collection();

        parent::__construct($notion);
    }

    public function query(): array
    {

        $filterJson = '
                {
                  "property": "Tags",
                  "multi_select": {
                    "contains": "great"
                  }
                }';


        $filter = json_decode($filterJson);

        if ($this->sorts->isNotEmpty())
            $postData["sorts"] = Sorting::sortQuery($this->sorts);

        if ($this->filter->isNotEmpty())
            $postData["filter"] = []; //Filter::filterQuery($this->filter);

        if($this->startCursor !== null)
            $postData["start_cursor"] = $this->startCursor;

        if($this->pageSize !== null)
            $postData["page_size"] = $this->pageSize;



        $response = $this->post(
            $this->url(Endpoint::DATABASES . "/{$this->databaseId}/query"),
            $postData
        )
            ->json();

        // toDo return Database Entity
        dd($response);
    }

    public function filterBy()
    {

        return $this;
    }

    public function sortBy(Collection $sortings)
    {
        $this->sorts = $sortings;

        return $this;
    }

    public function limit(int $limit)
    {
        $this->pageSize = min($limit, 100);

        return $this;
    }

    public function offset(StartCursor $startCursor)
    {
        // toDo
        throw WrapperException::instance("Not implemented yet.");

        $this->startCursor = $startCursor;
        return $this;
    }
}
