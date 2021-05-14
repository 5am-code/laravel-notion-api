<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Notion;

class Databases extends Endpoint implements EndpointInterface
{
    private string $databaseId;


    public function __construct(Notion $notion)
    {
        $this->notion = $notion;
        parent::__construct();
    }

    /**
     * List databases
     * url: https://api.notion.com/{version}/databases
     * notion-api-docs: https://developers.notion.com/reference/get-databases
     *
     * @return array
     */
    public function all(): array
    {
        return $this->getJson($this->url(Endpoint::DATABASES));
    }

    /**
     * Retrieve a database
     * url: https://api.notion.com/{version}/databases/{database_id}
     * notion-api-docs: https://developers.notion.com/reference/get-database
     *
     * @param string $databaseId
     * @return array
     */
    public function find(string $databaseId): Database
    {
        $jsonArray = $this->getJson(
            $this->url(Endpoint::DATABASES . "/{$databaseId}")
        );
        return new Database($jsonArray);
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
