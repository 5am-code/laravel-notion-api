<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\User;
use FiveamCode\LaravelNotionApi\Entities\UserCollection;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Collection;

class Users extends Endpoint implements EndpointInterface
{

    /**
     * List users
     * url: https://api.notion.com/{version}/users
     * notion-api-docs: https://developers.notion.com/reference/get-users
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->collect()->getResults();
    }
    
    
    /**
     * List users (raw json-data)
     * url: https://api.notion.com/{version}/users
     * notion-api-docs: https://developers.notion.com/reference/get-users
     *
     * @return array
     */
    public function allRaw(): array
    {
        return $this->collect()->getRawResults();
    }

    private function collect(): UserCollection{
        $result = $this->get(
            $this->url(Endpoint::USERS . "?{$this->buildPaginationQuery()}")
        );

        return new UserCollection($result->json());
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
        $response = $this->get(
            $this->url(Endpoint::USERS . "/" . $userId)
        );

        if (!$response->ok())
            throw WrapperException::instance("User not found.", ["userId" => $userId]);


        return new User($response->json());
    }
}
