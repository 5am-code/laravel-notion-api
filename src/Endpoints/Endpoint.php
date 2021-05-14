<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

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

    public function __construct()
    {
        $this->validVersions = collect(["v1"]);
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
}
