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

    protected ?StartCursor $startCursor = null;
    protected int $pageSize = 100;

    public function __construct(Notion $notion)
    {
        $this->notion = $notion;

        if ($this->notion->getConnection() === null) {
            throw WrapperException::instance("Connection could not be established, please check your token.");
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
