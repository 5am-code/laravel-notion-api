<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Collections\EntityCollection;
use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\Filter;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use Symfony\Component\VarDumper\Cloner\Data;

class Search extends Endpoint
{
    private string $searchText;
    private Collection $filter;
    private Collection $sorts;


    public function __construct(Notion $notion, string $searchText = "")
    {
        $this->sorts = new Collection();
        $this->filter = new Collection();
        $this->searchText = $searchText;

        parent::__construct($notion);
    }

    public function query(): Collection
    {
        $postData = [];

        // if ($this->sorts->isNotEmpty())
        //     $postData["sort"] = Sorting::sortQuery($this->sorts);

        // if ($this->filter->isNotEmpty())
        //     $postData["filter"]["or"] = Filter::filterQuery($this->filter); // TODO Compound filters!

        if ($this->startCursor !== null)
            $postData["start_cursor"] = $this->startCursor;

        if ($this->pageSize !== null)
            $postData["page_size"] = $this->pageSize;

        if($this->searchText !== null)
            $postData['query'] = $this->searchText;



        $response = $this
            ->post(
                $this->url(Endpoint::SEARCH),
                $postData
            )

            ->json();

        $pageCollection = new EntityCollection($response);

        return $pageCollection->getResults();
    }

    // public function filterBy(Collection $filter)
    // {
    //     $this->filter = $filter;
    //     return $this;
    // }

    // public function sortBy(Collection $sorts)
    // {
    //     $this->sorts = $sorts;
    //     return $this;
    // }
}
