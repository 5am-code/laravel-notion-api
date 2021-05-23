<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Carbon\Carbon;
use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
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

    protected function getPackageProviders($app)
    {
        return ['FiveamCode\LaravelNotionApi\LaravelNotionApiServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Notion' => \FiveamCode\LaravelNotionApi\NotionFacade::class
        ];
    }

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

        $result = \Notion::databases()->all()->asCollection();

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

        $result = \Notion::databases()->all()->asCollection();

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

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Bad Request");

        \Notion::databases()->all();
    }

    /** @test */
    public function it_returns_database_entity_with_filled_properties()
    {
        // successful /v1/databases/DATABASE_DOES_EXIST
        Http::fake([
            'https://api.notion.com/v1/databases/668d797c-76fa-4934-9b05-ad288df2d136'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/databases/response_specific_200.json'), true),
                200,
                ['Headers']
            )
        ]);

        $databaseResult = \Notion::databases()->find("668d797c-76fa-4934-9b05-ad288df2d136");

        $this->assertInstanceOf(Database::class, $databaseResult);

        // check properties
        $this->assertSame("Grocery List", $databaseResult->getTitle());
        $this->assertSame("database", $databaseResult->getObjectType());

        $this->assertCount(1, $databaseResult->getRawTitle());
        $this->assertCount(12, $databaseResult->getRawProperties());

        $this->assertInstanceOf(Carbon::class, $databaseResult->getCreatedTime());
        $this->assertInstanceOf(Carbon::class, $databaseResult->getLastEditedTime());
    }

    /** @test */
    public function it_throws_a_notion_exception_not_found()
    {
        // failing /v1/databases/DATABASE_DOES_NOT_EXIST
        Http::fake([
            'https://api.notion.com/v1/databases/b55c9c91-384d-452b-81db-d1ef79372b79'
            => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/databases/response_specific_404.json'), true),
                200,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage("Not found");

        \Notion::databases()->find("b55c9c91-384d-452b-81db-d1ef79372b79");
    }

}