<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;

class Endpoint
{
    const BASE_URL = 'https://api.notion.com/';
    const DATABASES = 'databases';
    const BLOCKS = 'blocks';
    const PAGES = 'pages';
    const USERS = 'users';
    const SEARCH = 'search';

    public Notion $notion;
    private Collection $validVersions;


    protected ?StartCursor $startCursor = null;
    protected ?int $pageSize = null;

    public function __construct(Notion $notion)
    {
        $this->validVersions = collect(["v1"]);
        $this->notion = $notion;
    }

    /**
     * Checks if given version for notion-api is valid
     *
     * @param string $version
     */
    public function checkValidVersion(string $version): void
    {
        if (!$this->validVersions->contains($version)) {
            throw WrapperException::instance("invalid version for notion-api", ['invalidVersion' => $version]);
        }
    }


    /**
     *
     * @param string $endpoint
     * @return string
     */
    protected function url(string $endpoint): string
    {
        return Endpoint::BASE_URL . "{$this->notion->getVersion()}/{$endpoint}";
    }


    /**
     *
     * @param string $url
     * @return array
     */
    protected function getJson(string $url): array
    {
        return $this->get($url)->json();
    }

    /**
     *
     */
    protected function get(string $url)
    {
        return $this->notion->getConnection()->get($url);
    }

    /**
     *
     */
    protected function post(string $url, array $body)
    {
        return $this->notion->getConnection()->post($url, $body);
    }


    protected function buildPaginationQuery(): string
    {
        $paginationQuery = "";

        if ($this->pageSize !== null)
            $paginationQuery = "page_size={$this->pageSize}&";

        if ($this->startCursor !== null)
            $paginationQuery .= "start_cursor={$this->startCursor}";

        return $paginationQuery;
    }

    public function limit(int $limit): Endpoint
    {
        $this->pageSize = min($limit, 100);

        return $this;
    }

    public function offset(StartCursor $startCursor): Endpoint
    {
        // toDo
        throw WrapperException::instance("Not implemented yet.");

        $this->startCursor = $startCursor;
        return $this;
    }

}
