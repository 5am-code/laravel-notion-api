<?php

namespace FiveamCode\LaravelNotionApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use FiveamCode\LaravelNotionApi\Endpoints\Pages;
use FiveamCode\LaravelNotionApi\Endpoints\Blocks;
use FiveamCode\LaravelNotionApi\Endpoints\Search;
use FiveamCode\LaravelNotionApi\Endpoints\Users;
use FiveamCode\LaravelNotionApi\Endpoints\Endpoint;
use FiveamCode\LaravelNotionApi\Endpoints\Database;
use FiveamCode\LaravelNotionApi\Endpoints\Databases;


class Notion
{
    private Endpoint $endpoint;
    private string $version;
    private string $token;
    private PendingRequest $connection;


    /**
     * Notion constructor.
     * @param string|null $version
     * @param string|null $token
     */
    public function __construct(string $version = null, string $token = null)
    {
        if ($token !== null) {
            $this->setToken($token);
        }

        $this->endpoint = new Endpoint($this);

        if ($version !== null) {
            $this->setVersion($version);
        } else {
            $this->v1();
        }
    }

    /**
     *
     * @return Notion
     */
    private function connect(): Notion
    {
        $this->connection = Http::withToken($this->token);
        return $this;
    }

    /**
     * Set version of notion-api
     *
     * @param string $version
     * @return Notion
     */
    public function setVersion(string $version): Notion
    {
        $this->endpoint->checkValidVersion($version);
        $this->version = $version;
        return $this;
    }

    /**
     * Wrapper function to set version to v1.
     *
     * @return $this
     */
    public function v1(): Notion
    {
        $this->setVersion("v1");
        return $this;
    }

    /**
     * Set notion-api bearer-token
     *
     * @param string $token
     * @return Notion
     */
    public function setToken(string $token): Notion
    {
        $this->token = $token;
        $this->connect();
        return $this;
    }


    /**
     * @return Databases
     */
    public function databases(): Databases
    {
        return new Databases($this);
    }

    /**
     * @return Database
     */
    public function database(string $databaseId): Database
    {
        return new Database($databaseId, $this);
    }

    /**
     * @return Pages
     */
    public function pages(): Pages
    {
        return new Pages($this);
    }

    /**
     * @return Blocks
     */
    public function blocks(): Blocks
    {
        return new Blocks($this);
    }

    /**
     * @return Users
     */
    public function users(): Users
    {
        return new Users($this);
    }

    /**
     * @return Search
     */
    public function search(): Search
    {
        return new Search($this);
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return PendingRequest
     */
    public function getConnection(): PendingRequest
    {
        return $this->connection;
    }
}
