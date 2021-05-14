<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\BlockCollection;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Collection;

class Block extends Endpoint
{
    private string $blockId;

    public function __construct(Notion $notion, string $blockId)
    {
        parent::__construct($notion);
        $this->blockId = $blockId;
    }

    /**
     * Retrieve block children (as raw json-data)
     * url: https://api.notion.com/{version}/blocks/{block_id}/children
     * notion-api-docs: https://developers.notion.com/reference/get-block-children
     *
     * @return BlockCollection
     */
    public function children(): Collection
    {
        return $this->collectChildren()->getResults();
    }

    /**
     * Retrieve block children
     * url: https://api.notion.com/{version}/blocks/{block_id}/children
     * notion-api-docs: https://developers.notion.com/reference/get-block-children
     *
     * @return BlockCollection
     */
    public function childrenRaw(): Collection
    {
        return $this->collectChildren()->getRawResults();
    }

    private function collectChildren(): BlockCollection
    {
        $response = $this->get(
            $this->url(Endpoint::BLOCKS . "/" . $this->blockId . "/children" . "?{$this->buildPaginationQuery()}")
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
