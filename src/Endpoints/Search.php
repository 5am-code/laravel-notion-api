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
    private ?string $filter = null;
    private ?Sorting $sort = null;


    public function __construct(Notion $notion, string $searchText = "")
    {
        $this->searchText = $searchText;
        parent::__construct($notion);
    }

    public function query(): Collection
    {
        $postData = [];

        if ($this->sort !== null)
            $postData["sort"] = $this->sort->toArray();

        if ($this->filter !== null)
            $postData["filter"] = ['property' => 'object', 'value' => $this->filter]; 

        if ($this->startCursor !== null)
            $postData["start_cursor"] = $this->startCursor;

        if ($this->pageSize !== null)
            $postData["page_size"] = $this->pageSize;

        if ($this->searchText !== null)
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

    public function sortByLastEditedTime(string $direction = "ascending"): Search
    {
        $this->sort = Sorting::timestampSort("last_edited_time", $direction);
        return $this;
    }

    public function onlyDatabases() : Search
    {
        $this->filter = "database";
        return $this;
    }

    public function onlyPages() : Search
    {
        $this->filter = "page";
        return $this;
    }

    public function getTitles()
    {
        $titleCollection = new Collection();
        $results = $this->query();

        foreach ($results as $result) {
            $titleCollection->add($result->getTitle());
        }

        return $titleCollection;
    }

    public function getIds(){
        $idCollection = new Collection();
        $results = $this->query();

        foreach ($results as $result) {
            $idCollection->add($result->getId());
        }
        return $idCollection;
    }

    public function filterBy(string $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function sortBy(Sorting $sort)
    {
        $this->sort = $sort;
        return $this;
    }
}
