<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Notion;

class Databases extends Endpoint implements EndpointInterface
{


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

}
