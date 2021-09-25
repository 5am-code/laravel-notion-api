<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use Notion;
use Illuminate\Support\Facades\Http;

/**
 * Class HandlingExceptionTest
 * @package FiveamCode\LaravelNotionApi\Tests
 */
class NotionExceptionTest extends NotionApiTest
{

    /** @test */
    public function it_throws_a_notion_exception_with_detailed_message_from_response()
    {

        Http::fake([
            'https://api.notion.com/v1/blocks/d092140ce4e549bf9915fb8ad43d1699d/children*'
            => Http::response(
                json_decode(file_get_contents("tests/stubs/endpoints/blocks/response_children_invalid_uuid_400.json"), true),
                400,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Bad Request: (validation_error) (path failed validation: path.id should be a valid uuid, instead was");

        \Notion::block("d092140ce4e549bf9915fb8ad43d1699d")->children()->asCollection();
    }
}