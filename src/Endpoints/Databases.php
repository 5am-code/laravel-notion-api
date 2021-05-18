<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Entities\Collections\DatabaseCollection;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Collection;


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
    public function all(): Collection
    {
        return $this->collect()->getResults();
    }

    /**
     * List databases (raw json-data)
     * url: https://api.notion.com/{version}/databases
     * notion-api-docs: https://developers.notion.com/reference/get-databases
     *
     * @return array
     */
    public function allRaw(): array
    {
        return $this->collect()->getRawResults();
    }

    // TODO rename this function - receive, access, fetch?
    private function collect(): DatabaseCollection
    {
        $resultData = $this->getJson($this->url(Endpoint::DATABASES) . "?{$this->buildPaginationQuery()}");
        $databaseCollection = new DatabaseCollection($resultData);
        return $databaseCollection;
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
        $response = $this->get(
            $this->url(Endpoint::DATABASES . "/{$databaseId}")
        );

        if (!$response->ok())
            throw HandlingException::instance("Database not found.", ["databaseId" => $databaseId]);

        return new Database($response->json());
    }
}
