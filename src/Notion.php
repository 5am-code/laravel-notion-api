<?php

namespace FiveamCode\LaravelNotionApi;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use FiveamCode\LaravelNotionApi\Endpoints\Pages;
use FiveamCode\LaravelNotionApi\Endpoints\Block;
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
    private ?PendingRequest $connection = null;

    private Collection $validVersions;

    /**
     * Notion constructor.
     * @param string|null $version
     * @param string|null $token
     */
    public function __construct(string $token, string $version = "v1")
    {
        $this->setToken($token);

        $this->validVersions = collect(["v1"]);

        $this->setVersion($version);

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
        $this->checkValidVersion($version);
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
     * @return Block
     */
    public function block(string $blockId): Block
    {
        return new Block($this, $blockId);
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
    public function search(?string $searchText = ""): Search
    {
        $search = new Search($this, $searchText);
        return $search;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return PendingRequest|null
     */
    public function getConnection(): ?PendingRequest
    {
        return $this->connection;
    }

    /**
     * Checks if given version for notion-api is valid
     *
     * @param string $version
     */
    public function checkValidVersion(string $version): void
    {
        if (!$this->validVersions->contains($version)) {
            throw HandlingException::instance("invalid version for notion-api", ['invalidVersion' => $version]);
        }
    }
}
