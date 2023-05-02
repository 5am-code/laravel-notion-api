<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Builder\DatabaseBuilder;
use FiveamCode\LaravelNotionApi\Entities\Collections\DatabaseCollection;
use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;

/**
 * Class Databases.
 *
 * This endpoint is not recommended by Notion anymore.
 * Use the search() endpoint instead.
 */
class Databases extends Endpoint implements EndpointInterface
{
    /**
     * List databases.
     *
     * @url https://api.notion.com/{version}/databases
     *
     * @reference https://developers.notion.com/reference/get-databases.
     *
     * @return DatabaseCollection
     *
     * @throws HandlingException
     * @throws NotionException
     *
     * @deprecated
     */
    public function all(): DatabaseCollection
    {
        $resultData = $this->getJson($this->url(Endpoint::DATABASES)."?{$this->buildPaginationQuery()}");

        return new DatabaseCollection($resultData);
    }

    /**
     * Retrieve a database.
     *
     * @url https://api.notion.com/{version}/databases/{database_id}
     *
     * @reference https://developers.notion.com/reference/retrieve-a-database.
     *
     * @param  string  $databaseId
     * @return Database
     *
     * @throws HandlingException
     * @throws NotionException
     */
    public function find(string $databaseId): Database
    {
        $result = $this
            ->getJson($this->url(Endpoint::DATABASES."/{$databaseId}"));

        return new Database($result);
    }

    /**
     * Returns a `DatabaseBuilder`reference, which helps building
     * the scheme and information for creation a database.
     *
     * @return DatabaseBuilder
     */
    public function build()
    {
        return new DatabaseBuilder($this);
    }

    /**
     * Create a database
     * Recommendation: use `build()` to eloquently create databases.
     *
     * @url https://api.notion.com/{version}/databases (post)
     *
     * @reference https://developers.notion.com/reference/create-a-database.
     *
     * @param  array  $payload
     * @return Database
     *
     * @throws HandlingException
     * @throws NotionException
     */
    public function create(array $payload): Database
    {
        $result = $this
            ->post($this->url(Endpoint::DATABASES), $payload);

        return new Database($result->json());
    }
}
