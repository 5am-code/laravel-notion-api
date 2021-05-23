<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Entities\Collections\DatabaseCollection;


/**
 * Class Databases
 *
 * This endpoint is not recommended by Notion anymore.
 * Use the search() endpoint instead.
 *
 * @package FiveamCode\LaravelNotionApi\Endpoints
 */
class Databases extends Endpoint implements EndpointInterface
{
    /**
     * List databases
     * url: https://api.notion.com/{version}/databases
     * notion-api-docs: https://developers.notion.com/reference/get-databases
     *
     * @return DatabaseCollection
     */
    public function all(): DatabaseCollection
    {
        $resultData = $this->getJson($this->url(Endpoint::DATABASES) . "?{$this->buildPaginationQuery()}");
        return new DatabaseCollection($resultData);
    }

    /**
     * Retrieve a database
     * url: https://api.notion.com/{version}/databases/{database_id}
     * notion-api-docs: https://developers.notion.com/reference/get-database
     *
     * @param string $databaseId
     * @return Database
     * @throws HandlingException
     */
    public function find(string $databaseId): Database
    {
        $result = $this
            ->getJson($this->url(Endpoint::DATABASES . "/{$databaseId}"));

        return new Database($result);
    }
}
