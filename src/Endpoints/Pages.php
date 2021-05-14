<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Notion;

class Pages extends Endpoint implements EndpointInterface
{

    public function __construct(Notion $notion)
    {
        $this->notion = $notion;
    }

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
        $jsonArray = $this->getJson(
            $this->url(Endpoint::PAGES . "/" . $pageId)
        );
        return new Page($this->notion, $jsonArray); 
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