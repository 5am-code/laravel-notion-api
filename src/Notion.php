<?php

namespace FiveamCode\LaravelNotionApi;

use Illuminate\Support\Facades\Http;
use FiveamCode\LaravelNotionApi\Endpoints\Blocks;
use FiveamCode\LaravelNotionApi\Endpoints\Databases;
use FiveamCode\LaravelNotionApi\Endpoints\Endpoint;
use FiveamCode\LaravelNotionApi\Endpoints\Pages;
use FiveamCode\LaravelNotionApi\Endpoints\Search;
use FiveamCode\LaravelNotionApi\Endpoints\Users;


class Notion
{
    private Endpoint $endpoint;
    private string $version;
    private string $token;
    private $connection;


    public function __construct(string $version = null, string $token = null)
    {
        $this->endpoint = new Endpoint();

        if ($version !== null) {
            $this->setVersion($version);
        }

        if ($token !== null) {
            $this->setToken($token);
        }
    }

    /**
     * 
     * @return Notion
     */
    private function connect()
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

    public function v1(){
        $this->setVersion("v1");
        return $this;
    }

        /**
     * Set notion-api bearer-token
     * 
     * @param string $token
     * @return Notion
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->connect();
        return $this;
    }


    public function databases(){
        return new Databases($this);
    }
    public function pages(){
        return new Pages($this);
    }
    public function blocks(){
        return new Blocks($this);
    }

    public function users(){
        return new Users($this);
    }

    public function search(){
        return new Search($this);
    }

    public function getVersion(){
        return $this->version;
    }

    public function getConnection(){
        return $this->connection;
    }
}
