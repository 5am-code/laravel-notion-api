<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Entities\Collections\BlockCollection;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block as BlockEntity;
/**
 * Class Block
 * @package FiveamCode\LaravelNotionApi\Endpoints
 */
class Block extends Endpoint
{
    /**
     * @var string
     */
    private string $blockId;

    /**
     * Block constructor.
     * @param Notion $notion
     * @param string $blockId
     * @throws HandlingException
     * @throws \FiveamCode\LaravelNotionApi\Exceptions\LaravelNotionAPIException
     */
    public function __construct(Notion $notion, string $blockId)
    {
        parent::__construct($notion);
        $this->blockId = $blockId;
    }

    /**
     * Retrieve a block
     * url: https://api.notion.com/{version}/blocks/{block_id}
     * notion-api-docs: https://developers.notion.com/reference/retrieve-a-block
     *
     * @param string $blockId
     * @return BlockEntity
     * @throws HandlingException
     * @throws NotionException
     */
    public function retrieve(): BlockEntity {

        $response = $this->get(
            $this->url(Endpoint::BLOCKS . '/' . $this->blockId)
        );

        return BlockEntity::fromResponse($response->json());
    }

    /**
     * Retrieve block children
     * url: https://api.notion.com/{version}/blocks/{block_id}/children
     * notion-api-docs: https://developers.notion.com/reference/get-block-children
     *
     * @return BlockCollection
     * @throws HandlingException
     * @throws NotionException
     */
    public function children(): BlockCollection
    {
        $response = $this->get(
            $this->url(Endpoint::BLOCKS . '/' . $this->blockId . '/children' . "?{$this->buildPaginationQuery()}")
        );

        return new BlockCollection($response->json());
    }

    /**
     * @return array
     * @throws HandlingException
     */
    public function create(): array
    {
        throw new HandlingException('Not implemented');
    }
}
