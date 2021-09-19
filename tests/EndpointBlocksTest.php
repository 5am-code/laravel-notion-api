<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use Notion;
use Illuminate\Support\Facades\Http;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;
use FiveamCode\LaravelNotionApi\Entities\Blocks\BulletedListItem;
use FiveamCode\LaravelNotionApi\Entities\Blocks\HeadingOne;
use FiveamCode\LaravelNotionApi\Entities\Blocks\HeadingThree;
use FiveamCode\LaravelNotionApi\Entities\Blocks\HeadingTwo;
use FiveamCode\LaravelNotionApi\Entities\Blocks\NumberedListItem;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Paragraph;
use FiveamCode\LaravelNotionApi\Entities\Blocks\ToDo;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Toggle;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
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
        $this->expectExceptionMessage('Bad Request');

        Notion::block('b55c9c91-384d-452b-81db-d1ef79372b76')->children();
    }

    /** @test */
    public function it_returns_block_collection_with_children()
    {
        // successful /v1/blocks/BLOCK_DOES_EXIST/children
        Http::fake([
            'https://api.notion.com/v1/blocks/b55c9c91-384d-452b-81db-d1ef79372b76/children*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_block_children_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $blockChildren = Notion::block('b55c9c91-384d-452b-81db-d1ef79372b76')->children();
        $this->assertInstanceOf(BlockCollection::class, $blockChildren);

        $blockChildrenCollection = $blockChildren->asCollection();
        $this->assertContainsOnly(Block::class, $blockChildrenCollection);
        $this->assertIsIterable($blockChildrenCollection);
        $this->assertCount(3, $blockChildrenCollection);

        // check first child
        $blockChild = $blockChildrenCollection->first();
        $this->assertInstanceOf(Block::class, $blockChild);
        $this->assertEquals('heading_2', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertArrayHasKey('text', $blockChild->getRawContent());
        $this->assertArrayHasKey(0, $blockChild->getRawContent()['text']);
        $this->assertArrayHasKey('plain_text', $blockChild->getRawContent()['text'][0]);
        $this->assertEquals('Lacinato kale', $blockChild->getRawContent()['text'][0]['plain_text']);
    }

    /** @test */
    public function it_returns_block_collection_with_children_as_correct_instances()
    {
        // successful /v1/blocks/BLOCK_DOES_EXIST/children
        Http::fake([
            'https://api.notion.com/v1/blocks/1d719dd1-563b-4387-b74f-20da92b827fb/children*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_supported_blocks_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $blockChildren = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->children();
        $this->assertInstanceOf(BlockCollection::class, $blockChildren);

        # check collection
        $blockChildrenCollection = $blockChildren->asCollection();
        $this->assertContainsOnly(Block::class, $blockChildrenCollection);
        $this->assertIsIterable($blockChildrenCollection);
        $this->assertCount(8, $blockChildrenCollection);

        # check paragraph
        $blockChild = $blockChildrenCollection[0];
        $this->assertInstanceOf(Paragraph::class, $blockChild);
        $this->assertEquals('paragraph', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('paragraph_block', $blockChild->getContent()->getPlainText());

        # check heading_1
        $blockChild = $blockChildrenCollection[1];
        $this->assertInstanceOf(HeadingOne::class, $blockChild);
        $this->assertEquals('heading_1', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('heading_one_block', $blockChild->getContent()->getPlainText());

        # check heading_2
        $blockChild = $blockChildrenCollection[2];
        $this->assertInstanceOf(HeadingTwo::class, $blockChild);
        $this->assertEquals('heading_2', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('heading_two_block', $blockChild->getContent()->getPlainText());

        # check heading_3
        $blockChild = $blockChildrenCollection[3];
        $this->assertInstanceOf(HeadingThree::class, $blockChild);
        $this->assertEquals('heading_3', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('heading_three_block', $blockChild->getContent()->getPlainText());

        # check bulleted_list_item
        $blockChild = $blockChildrenCollection[4];
        $this->assertInstanceOf(BulletedListItem::class, $blockChild);
        $this->assertEquals('bulleted_list_item', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('bulleted_list_item_block', $blockChild->getContent()->getPlainText());

        # check numbered_list_item
        $blockChild = $blockChildrenCollection[5];
        $this->assertInstanceOf(NumberedListItem::class, $blockChild);
        $this->assertEquals('numbered_list_item', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('numbered_list_item_block', $blockChild->getContent()->getPlainText());

        # check to_do
        $blockChild = $blockChildrenCollection[6];
        $this->assertInstanceOf(ToDo::class, $blockChild);
        $this->assertEquals('to_do', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('to_do_block', $blockChild->getContent()->getPlainText());

        # check toggle
        $blockChild = $blockChildrenCollection[7];
        $this->assertInstanceOf(Toggle::class, $blockChild);
        $this->assertEquals('toggle', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('toggle_block', $blockChild->getContent()->getPlainText());

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
        $this->expectExceptionMessage('Not found');

        Notion::block('b55c9c91-384d-452b-81db-d1ef79372b11')->children();
    }

    /** @test */
    public function it_throws_a_handling_exception_not_implemented()
    {

        $this->expectException(HandlingException::class);
        $this->expectExceptionMessage('Not implemented');

        Notion::block('')->create();
    }

    /** @test */
    public function it_retrieves_a_single_block()
    {
        // successful /v1/blocks/BLOCK_DOES_EXIST
        Http::fake([
            'https://api.notion.com/v1/blocks/a6f8ebe8d5df4ffab543bcd54d1c3bad'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_block_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $block = \Notion::block("a6f8ebe8d5df4ffab543bcd54d1c3bad")->retrieve();

        $this->assertInstanceOf(Block::class, $block);
        $this->assertInstanceOf(Paragraph::class, $block);
    }

}