<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

/**
 * Class EndpointDatabaseTest
 *
 * The fake API responses are based on Notions documentation.
 * @see https://developers.notion.com/reference/get-databases
 *
 * @package FiveamCode\LaravelNotionApi\Tests
 */
class EndpointDatabaseTest extends TestCase
{


    /** @test */
    public function it_returns_a_list_of_database_objects()
    {
        // successful /v1/databases
        Http::fake([
            'https://api.notion.com/v1/databases*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/databases/response_all_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $notion = new Notion();
        $notion->v1()->setToken("secret_*");

        $result = $notion->databases()->all();

        $this->assertIsIterable($result);
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_returns_an_empty_list()
    {
        // successful but empty /v1/databases
        Http::fake([
            'https://api.notion.com/v1/databases*'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/databases/response_empty_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $notion = new Notion();
        $notion->v1()->setToken("secret_*");

        $result = $notion->databases()->all();

        // TODO check class here
        $this->assertIsIterable($result);
        $this->assertCount(0, $result);
    }

    /** @test */
    public function it_throws_a_notion_exception_bad_request()
    {
        // failing /v1/databases
        Http::fake([
            'https://api.notion.com/v1/databases*'
            => Http::response(
                json_decode('{}', true),
                400,
                ['Headers']
            )
        ]);
        $notion = new Notion();
        $notion->v1()->setToken("secret_*");


        $this->expectException(NotionException::class);

        $result = $notion->databases()->all();
    }


}