<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\User;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\StartCursor;

class Users extends Endpoint implements EndpointInterface
{

    /**
     * List users
     * url: https://api.notion.com/{version}/users
     * notion-api-docs: https://developers.notion.com/reference/get-users
     *
     * @return array
     */
    public function all(): array
    {
        return $this->getJson($this->url(Endpoint::USERS . "?{$this->buildPaginationQuery()}"));
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

}
