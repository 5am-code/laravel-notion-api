<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Notion;
use Illuminate\Support\Facades\Http;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;
use FiveamCode\LaravelNotionApi\Entities\Blocks\BulletedListItem;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Embed;
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
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_200.json'), true),
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
        $this->assertCount(9, $blockChildrenCollection);

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

        # check embed
        $blockChild = $blockChildrenCollection[8];
        $this->assertInstanceOf(Embed::class, $blockChild);
        $this->assertEquals('embed', $blockChild->getType());
        $this->assertFalse($blockChild->hasChildren());
        $this->assertEquals('Testcaption', $blockChild->getCaption()->getPlainText());
        $this->assertEquals('https://notion.so', $blockChild->getUrl());
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
    public function it_returns_parent_block_in_which_new_blocks_have_been_successfully_appended()
    {
        //TODO: Change in release 0.7.0 or 0.8.0
        //!IMPORTANT: This will be changed in Notion version 2021-08-16, because a list of the newly created block children will be returned
        //!https://developers.notion.com/changelog/notion-version-2021-08-16#append-block-children-returns-a-list-of-blocks

        // successful /v1/blocks/BLOCK_DOES_EXIST/children [patch]
        Http::fake([
            'https://api.notion.com/v1/blocks/1d719dd1-563b-4387-b74f-20da92b827fb/children*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/blocks/response_specific_block_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $paragraph = Paragraph::create("New TextBlock");
        $bulletedListItem = BulletedListItem::create("New TextBlock");
        $headingOne = HeadingOne::create("New TextBlock");
        $headingTwo = HeadingTwo::create("New TextBlock");
        $headingThree = HeadingThree::create("New TextBlock");
        $numberedListItem = NumberedListItem::create("New TextBlock");
        $toDo = ToDo::create("New TextBlock");
        $toggle = Toggle::create(["New TextBlock"]);
        $embed = Embed::create("https://5amco.de", "Testcaption");

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($paragraph);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($bulletedListItem);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($headingOne);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($headingTwo);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($headingThree);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($numberedListItem);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($toDo);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($toggle);
        $this->assertInstanceOf(Block::class, $parentBlock);

        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append($embed);
        $this->assertInstanceOf(Block::class, $parentBlock);


        $parentBlock = Notion::block('1d719dd1-563b-4387-b74f-20da92b827fb')->append([$paragraph, $bulletedListItem, $headingOne, $headingTwo, $headingThree, $numberedListItem, $toDo, $toggle, $embed]);
        $this->assertInstanceOf(Block::class, $parentBlock);
    }
}
