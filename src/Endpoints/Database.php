<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;


use FiveamCode\LaravelNotionApi\Notion;
use \FiveamCode\LaravelNotionApi\Entities\Database as DatabaseEntity;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use Illuminate\Support\Collection;

class Database extends Endpoint
{
    private string $databaseId;
    private Collection $sortings;

    public function __construct(string $databaseId, Notion $notion)
    {
        $this->databaseId = $databaseId;
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


        $sortingJson = '{
        "property": "Ordered",
	      "timestamp": "created_time",
	      "direction": "descending"
	    }';


        $filter = json_decode($filterJson);

        if($this->sortings->isNotEmpty())
            $postData["sorts"] = Sorting::sortQuery($this->sortings);

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
        $this->sortings = $sortings;

        return $this;
    }

    public function limit()
    {

        return $this;
    }

    public function offset()
    {

        return $this;
    }
}
