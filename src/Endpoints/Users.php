<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\User;
use FiveamCode\LaravelNotionApi\Notion;

class Users extends Endpoint implements EndpointInterface
{

    public function __construct(Notion $notion)
    {
        $this->notion = $notion;
    }

    /**
     * List users
     * url: https://api.notion.com/{version}/users
     * notion-api-docs: https://developers.notion.com/reference/get-users
     *
     * @return array
     */
    public function all(): array
    {
        // toDo: Limit & offset, rename to get() like in eloquent?
        return $this->getJson($this->url(Endpoint::USERS));
    }

    /**
     * Retrieve a user
     * url: https://api.notion.com/{version}/users/{user_id}
     * notion-api-docs: https://developers.notion.com/reference/get-user
     *
     * @param string $userId
     * @return array
     */
    public function find(string $userId): User
    {
        $jsonArray = $this->getJson(
            $this->url(Endpoint::USERS . "/" . $userId)
        );
        return new User($jsonArray);
    }

    public function limit()
    {
        //toDo
        throw new \Exception("not implemented yet");
    }

    public function offset()
    {
        //toDo
        throw new \Exception("not implemented yet");
    }
}
