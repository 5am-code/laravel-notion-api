<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Illuminate\Support\Facades\Http;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Entities\Collections\BlockCollection;

/**
 * Class EndpointBlocksTest
 *
 * The fake API responses are based on Notions documentation.
 * @see https://developers.notion.com/reference/get-block-children
 *
 * @package FiveamCode\LaravelNotionApi\Tests
 */
class EndpointBlocksTest extends NotionApiTest
{

    /** @test */
    public function it_throws_a_notion_exception_bad_request()
    {
        // failing /v1/blocks
        Http::fake([
            'https://api.notion.com/v1/blocks/b55c9c91-384d-452b-81db-d1ef79372b76/children*'
            => Http::response(
                json_decode('{}', true),
                400,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Bad Request");

        \Notion::block("b55c9c91-384d-452b-81db-d1ef79372b76")->children();
    }

    /** @test */
    public function it_returns_block_collection_with_children()
    {
        // successful /v1/blocks/BLOCK_DOES_EXIST/children
        Http::fake([
            'https://api.notion.com/v1/blocks/b55c9c91-384d-452b-81db-d1ef79372b76/children*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $blockChildren = \Notion::block("b55c9c91-384d-452b-81db-d1ef79372b76")->children();
        $this->assertInstanceOf(BlockCollection::class, $blockChildren);

        $blockChildrenCollection = $blockChildren->asCollection();
        $this->assertContainsOnly(Block::class, $blockChildrenCollection);
        $this->assertIsIterable($blockChildrenCollection);
        $this->assertCount(3, $blockChildrenCollection);

        // check first child
        $blockChild = $blockChildrenCollection->first();
        $this->assertInstanceOf(Block::class, $blockChild);
        $this->assertEquals("heading_2", $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertArrayHasKey("text", $blockChild->getRawContent());
        $this->assertArrayHasKey(0, $blockChild->getRawContent()["text"]);
        $this->assertArrayHasKey("plain_text", $blockChild->getRawContent()["text"][0]);
        $this->assertEquals("Lacinato kale", $blockChild->getRawContent()["text"][0]["plain_text"]);
    }

    /** @test */
    public function it_throws_a_notion_exception_not_found()
    {
        // failing /v1/blocks/BLOCK_DOES_NOT_EXIST/children
        Http::fake([
            'https://api.notion.com/v1/blocks/b55c9c91-384d-452b-81db-d1ef79372b11*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_404.json'), true),
                200,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Not found");

        \Notion::block("b55c9c91-384d-452b-81db-d1ef79372b11")->children();
    }

}