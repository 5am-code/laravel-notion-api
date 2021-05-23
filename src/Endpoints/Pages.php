<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

class Pages extends Endpoint implements EndpointInterface
{

    /**
     * Retrieve a page
     * url: https://api.notion.com/{version}/pages/{page_id}
     * notion-api-docs: https://developers.notion.com/reference/get-page
     *
     * @param string $pageId
     * @return array
     */
    public function find(string $pageId): Page
    {
        $response = $this->get(
            $this->url(Endpoint::PAGES . "/" . $pageId)
        );

        if(!$response->ok())
            throw HandlingException::instance("Page not found.", ["pageId" => $pageId]);

        return new Page($response->json());
    }

    public function create(): array{
        //toDo
        throw new \Exception("not implemented yet");
    }


    public function updateProperties(): array{
        //toDo
        throw new \Exception("not implemented yet");
    }
}