<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Pages
 * @package FiveamCode\LaravelNotionApi\Endpoints
 */
class Pages extends Endpoint implements EndpointInterface
{

    /**
     * Retrieve a page
     * url: https://api.notion.com/{version}/pages/{page_id}
     * notion-api-docs: https://developers.notion.com/reference/get-page
     *
     * @param string $pageId
     * @return Page
     * @throws HandlingException
     * @throws NotionException
     */
    public function find(string $pageId): Page
    {
        $response = $this->get(
            $this->url(Endpoint::PAGES . '/' . $pageId)
        );

        return new Page($response->json());
    }

    /**
     * @return array
     * @throws HandlingException
     */
    public function create(): array
    {
        //toDo
        throw new HandlingException('Not implemented');
    }


    /**
     * @return array
     * @throws HandlingException
     */
    public function updateProperties(): array
    {
        //toDo
        throw new HandlingException('Not implemented');
    }
}