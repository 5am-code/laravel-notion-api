<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Carbon\Carbon;
use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

/**
 * Class EndpointPageTest
 *
 * The fake API responses are based on our test environment (since the current Notion examples do not match with the actual calls).
 * @see https://developers.notion.com/reference/get-page
 *
 * @package FiveamCode\LaravelNotionApi\Tests
 */
class EndpointPagesTest extends NotionApiTest
{

    /** @test */
    public function it_throws_a_notion_exception_bad_request()
    {
        // failing /v1/databases
        Http::fake([
            'https://api.notion.com/v1/pages*'
            => Http::response(
                json_decode('{}', true),
                400,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Bad Request");

        \Notion::pages()->find("afd5f6fb-1cbd-41d1-a108-a22ae0d9bac8");
    }

    /** @test */
    public function it_returns_page_entity_with_filled_properties()
    {
        // successful /v1/pages/PAGE_DOES_EXIST
        Http::fake([
            'https://api.notion.com/v1/pages/afd5f6fb-1cbd-41d1-a108-a22ae0d9bac8'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/pages/response_specific_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $pageResult = \Notion::pages()->find("afd5f6fb-1cbd-41d1-a108-a22ae0d9bac8");

        $this->assertInstanceOf(Page::class, $pageResult);

        // check properties
        $this->assertSame("Notion Is Awesome", $pageResult->getTitle());
        $this->assertSame("page", $pageResult->getObjectType());

        $this->assertCount(6, $pageResult->getRawProperties());

        $this->assertInstanceOf(Carbon::class, $pageResult->getCreatedTime());
        $this->assertInstanceOf(Carbon::class, $pageResult->getLastEditedTime());
    }

    /** @test */
    public function it_throws_a_notion_exception_not_found()
    {
        // failing /v1/pages/PAGE_DOES_NOT_EXIST
        Http::fake([
            'https://api.notion.com/v1/pages/b55c9c91-384d-452b-81db-d1ef79372b79'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/pages/response_specific_404.json'), true),
                200,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Not found");

        \Notion::pages()->find("b55c9c91-384d-452b-81db-d1ef79372b79");
    }

}