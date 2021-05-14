<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Block;
use FiveamCode\LaravelNotionApi\Notion;

class Blocks extends Endpoint implements EndpointInterface
{
    public function __construct(Notion $notion)
    {
        $this->notion = $notion;
    }

    /**
     * Retrieve block children
     * url: https://api.notion.com/{version}/blocks/{block_id}/children
     * notion-api-docs: https://developers.notion.com/reference/get-block-children
     *
     * @param string $blockId
     * @return array
     */
    public function find(string $blockId): Block
    {
        $jsonArray = $this->getJson(
            $this->url(Endpoint::BLOCKS . "/" . $blockId . "/children")
        );
        return new Block($jsonArray);
    }

    public function create(): array{
        //toDo
        throw new \Exception("not implemented yet");
    }
}
