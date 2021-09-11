<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Entities\Collections\BlockCollection;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block as BaseBlockEntity;

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
     * Retrieve block children
     * url: https://api.notion.com/{version}/blocks/{block_id}/children [get]
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
     * Append one Block or an array of Blocks
     * url: https://api.notion.com/{version}/blocks/{block_id}/children [patch]
     * notion-api-docs: https://developers.notion.com/reference/patch-block-children
     *
     * @return FiveamCode\LaravelNotionApi\Entities\Blocks\Block
     * @throws HandlingException
     */
    public function append(array|BaseBlockEntity $appendices): BaseBlockEntity
    {
        if (!is_array($appendices)) {
            $appendices = [$appendices];
        }
        $children = [];

        foreach ($appendices as $block) {
            array_push($children, [
                "object" => "block",
                "type" => $block->getType(),
                $block->getType() => $block->getRawContent()
            ]);
        }

        $body = [
            "children" => $children
        ];

        $response = $this->patch(
            $this->url(Endpoint::BLOCKS . '/' . $this->blockId . '/children' . ""),
            $body
        );

        return new BaseBlockEntity($response->json());
    }
}
