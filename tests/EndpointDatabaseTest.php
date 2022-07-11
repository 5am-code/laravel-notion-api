<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Endpoints\Database;
use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Notion;

/**
 * Class EndpointDatabaseTest.
 *
 * Due to the complexity of the query request, there are also tests
 * for building the correct request body required, alongside tests
 * for processing the API response.
 *
 * We are using our own Notion database for better traceability and
 * understanding. Nevertheless, the responses are stored in the stubs
 * folder so you don't have to worry about access when running
 * your own tests.
 *
 * @see https://www.notion.so/8284f3ff77e24d4a939d19459e4d6bdc?v=bc3a9ce8cdb84d3faefc9ae490136ac2
 * @see https://developers.notion.com/reference/post-database-query
 */
class EndpointDatabaseTest extends NotionApiTest
{
    /** @test */
    public function it_returns_a_database_endpoint_instance()
    {
        $endpoint = Notion::database('897e5a76ae524b489fdfe71f5945d1af');

        $this->assertInstanceOf(Database::class, $endpoint);
    }

    /**
     * @dataProvider limitProvider
     */
    public function limitProvider(): array
    {
        return [
            [1],
            [2],
        ];
    }

    /**
     * @test
     * @dataProvider limitProvider
     *
     * @param $limit
     */
    public function it_queries_a_database_with_filter_and_sorting_and_processes_result($limit)
    {
        // success /v1/databases/DATABASE_DOES_EXIST/query
        Http::fake([
            'https://api.notion.com/v1/databases/8284f3ff77e24d4a939d19459e4d6bdc/query*' => Http::response(
                json_decode(file_get_contents("tests/stubs/endpoints/databases/response_query_limit{$limit}_200.json"), true),
                200,
                ['Headers']
            ),
        ]);

        // Let's search for women developing the UNIVAC I computer
        // and sort them by birth year descending
        $sortings = new Collection();
        $filters = new Collection();

        $sortings->add(
            Sorting::propertySort('Birth year', 'descending')
        );

        $filters
            ->add(
                Filter::rawFilter(
                    'Known for',
                    [
                        'multi_select' => ['contains' => 'UNIVAC'],
                    ]
                )
            );

        $result = Notion::database('8284f3ff77e24d4a939d19459e4d6bdc')
            ->filterBy($filters)
            ->sortBy($sortings)
            ->limit($limit)
            ->query();

        $this->assertInstanceOf(PageCollection::class, $result);

        $resultCollection = $result->asCollection();

        $this->assertIsIterable($resultCollection);
        $this->assertCount($limit, $resultCollection);
        $this->assertContainsOnly(Page::class, $resultCollection);

        // check page object
        $page = $resultCollection->first();
        $this->assertEquals('Betty Holberton', $page->getTitle());
    }

    /** @test */
    public function it_queries_a_database_with_filter_and_sorting_and_has_empty_result()
    {
        // success /v1/databases/DATABASE_DOES_EXIST/query
        Http::fake([
            'https://api.notion.com/v1/databases/8284f3ff77e24d4a939d19459e4d6bdc/query*' => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/databases/response_query_no_result_200.json'), true),
                200,
                ['Headers']
            ),
        ]);

        // Let's search for something that doesn't exists
        $filters = new Collection();

        $filters
            ->add(
                Filter::rawFilter(
                    'Known for',
                    [
                        'multi_select' => ['contains' => "something that doesn't exists"],
                    ]
                )
            );

        $result = Notion::database('8284f3ff77e24d4a939d19459e4d6bdc')
            ->filterBy($filters)
            ->query();

        $this->assertInstanceOf(PageCollection::class, $result);

        $resultCollection = $result->asCollection();

        $this->assertIsIterable($resultCollection);
        $this->assertCount(0, $resultCollection);
    }

    /** @test */
    public function it_throws_a_notion_exception_bad_request()
    {
        // failing /v1/databases
        Http::fake([
            'https://api.notion.com/v1/databases/8284f3ff77e24d4a939d19459e4d6bdc/query*' => Http::response(
                json_decode('{}', true),
                400,
                ['Headers']
            ),
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage('Bad Request');

        Notion::database('8284f3ff77e24d4a939d19459e4d6bdc')->query();
    }

    /** @test */
    public function it_queries_a_database_with_and_without_offset_and_processes_result()
    {
        // success /v1/databases/DATABASE_DOES_EXIST/query
        Http::fake([
            'https://api.notion.com/v1/databases/8284f3ff77e24d4a939d19459e4d6bdc/query*' => Http::sequence()
                ->push(
                    json_decode(file_get_contents('tests/stubs/endpoints/databases/response_query_offset_start_200.json'), true),
                    200,
                    ['Headers']
                )
                ->push(
                    json_decode(file_get_contents('tests/stubs/endpoints/databases/response_query_offset_end_200.json'), true),
                    200,
                    ['Headers']
                ),
        ]);

        $result = Notion::database('8284f3ff77e24d4a939d19459e4d6bdc')
            ->query();

        //check instance and offset
        $this->assertInstanceOf(PageCollection::class, $result);
        $this->assertEquals(true, $result->hasMoreEntries());
        $this->assertInstanceOf(StartCursor::class, $result->nextCursor());
        $this->assertEquals('1500b7c7-329f-4854-8912-4c6972a8743e', $result->nextCursor());
        $this->assertEquals('1500b7c7-329f-4854-8912-4c6972a8743e', $result->getRawNextCursor());

        $resultCollection = $result->asCollection();

        $this->assertIsIterable($resultCollection);
        $this->assertContainsOnly(Page::class, $resultCollection);

        // check page object
        $page = $resultCollection->first();
        $this->assertEquals('Betty Holberton', $page->getTitle());

        $resultWithOffset = Notion::database('8284f3ff77e24d4a939d19459e4d6bdc')
            ->offsetByResponse($result)
            ->query();

        // check instance and offset
        $this->assertInstanceOf(PageCollection::class, $resultWithOffset);
        $this->assertEquals(false, $resultWithOffset->hasMoreEntries());
        $this->assertEquals(null, $resultWithOffset->nextCursor());
        $this->assertEquals(null, $resultWithOffset->getRawNextCursor());

        $resultWithOffsetCollection = $resultWithOffset->asCollection();

        $this->assertIsIterable($resultWithOffsetCollection);
        $this->assertContainsOnly(Page::class, $resultWithOffsetCollection);

        // check page object
        $page = $resultWithOffsetCollection->first();
        $this->assertEquals('Betty Holberton', $page->getTitle());
    }

    /**
     * @test
     * ! edge-case
     */
    public function it_queries_a_database_with_a_rollup_property_with_empty_selects()
    {
        // success /v1/databases/DATABASE_DOES_EXIST/query
        Http::fake([
            'https://api.notion.com/v1/databases/11971214ce574df7a58389c1deda61d7/query*' => Http::response(
                json_decode(file_get_contents('tests/stubs/endpoints/databases/response_query_rollup_empty_select_200.json'), true),
                200,
                ['Headers']
            ),
        ]);

        $result = Notion::database('11971214ce574df7a58389c1deda61d7')->query();

        $this->assertInstanceOf(PageCollection::class, $result);

        $resultCollection = $result->asCollection();

        $this->assertIsIterable($resultCollection);
        $this->assertContainsOnly(Page::class, $resultCollection);

        // check page object
        $page = $resultCollection->first();
        $this->assertEquals(0, $page->getProperty('Rollup')->getContent()->count());
    }
}
