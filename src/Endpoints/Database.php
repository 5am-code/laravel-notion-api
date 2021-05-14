<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;


use FiveamCode\LaravelNotionApi\Notion;

class Database extends Endpoint
{
    private string $databaseId;

    public function __construct(string $databaseId, Notion $notion)
    {
        $this->databaseId = $databaseId;
        parent::__construct($notion);
    }

    public function query(string $databaseId): array
    {

        $filterJson = '
                {
                  "property": "Tags",
                  "multi_select": {
                    "contains": "great"
                  }
                }';

        $filter = json_decode($filterJson);
        $postData = ["filter" => $filter];

        $response = $this->post(
            $this->url(Endpoint::DATABASES . "/{$databaseId}/query"),
            $postData
        )
            ->json();

        dump($response);
        return [];
    }

    public function filterBy()
    {
    }

    public function sortBy()
    {
    }

    public function limit()
    {
    }

    public function offset()
    {
    }
}
