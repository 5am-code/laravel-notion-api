<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Entities\Collections\BlockCollection;

class Block extends Endpoint
{
    private string $blockId;

    public function __construct(Notion $notion, string $blockId)
    {
        parent::__construct($notion);
        $this->blockId = $blockId;
    }

    /**
     * Retrieve block children
     * url: https://api.notion.com/{version}/blocks/{block_id}/children
     * notion-api-docs: https://developers.notion.com/reference/get-block-children
     *
     * @return BlockCollection
     */
    public function children(): BlockCollection
    {
        $response = $this->get(
            $this->url(Endpoint::BLOCKS . "/" . $this->blockId . "/children" . "?{$this->buildPaginationQuery()}")
        );

        return new BlockCollection($response->json());
    }

    public function create(): array
    {
        throw new HandlingException("Not implemented");
    }
}
