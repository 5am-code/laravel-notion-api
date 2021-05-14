<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\BlockCollection;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Collection;

class Block extends Endpoint
{
    private string $blockId;

    public function __construct(Notion $notion, string $blockId){
        parent::__construct($notion);
        $this->blockId = $blockId;
    }

    /**
     * Retrieve block children
     * url: https://api.notion.com/{version}/blocks/{block_id}/children
     * notion-api-docs: https://developers.notion.com/reference/get-block-children
     *
     * @param string $blockId
     * @return BlockCollection
     */
    public function children(): BlockCollection
    {
        $response = $this->get(
            $this->url(Endpoint::BLOCKS . "/" . $this->blockId . "/children")
        );

        if (!$response->ok())
            throw WrapperException::instance("Block not found.", ["blockId" => $this->blockId]);


        $blockCollection = new BlockCollection($response->json());

        return $blockCollection;
    }

    public function create(): array
    {
        //toDo
        throw new \Exception("not implemented yet");
    }
}
