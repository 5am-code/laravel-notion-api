<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

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

    protected ?\Illuminate\Http\Client\Response $response = null;

    public function __construct(Notion $notion)
    {
        $this->notion = $notion;

        if ($this->notion->getConnection() === null) {
            throw HandlingException::instance("Connection could not be established, please check your token.");
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
        if ($this->response === null)
            $this->get($url);

        return $this->response->json();
    }

    /**
     *
     */
    protected function get(string $url)
    {
        $response = $this->notion->getConnection()->get($url);

        if ($response->failed())
            throw NotionException::fromResponse($response);

        $this->response = $response;
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
        throw HandlingException::instance("Not implemented yet.");

        $this->startCursor = $startCursor;
        return $this;
    }

}
