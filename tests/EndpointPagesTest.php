<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Entities\Properties\Checkbox;
use FiveamCode\LaravelNotionApi\Entities\Properties\Date;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichDate;
use Notion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

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
        // failing /v1
        Http::fake([
            'https://api.notion.com/v1/pages*'
            => Http::response(
                json_decode('{}', true),
                400,
                ['Headers']
            )
        ]);

        $this->expectException(NotionException::class);
        $this->expectExceptionMessage('Bad Request');

        Notion::pages()->find('afd5f6fb-1cbd-41d1-a108-a22ae0d9bac8');
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

        $pageResult = Notion::pages()->find('afd5f6fb-1cbd-41d1-a108-a22ae0d9bac8');

        $this->assertInstanceOf(Page::class, $pageResult);

        // check properties
        $this->assertSame('Notion Is Awesome', $pageResult->getTitle());
        $this->assertSame('page', $pageResult->getObjectType());
        $this->assertCount(7, $pageResult->getRawProperties());
        $this->assertCount(7, $pageResult->getProperties());
        $this->assertCount(7, $pageResult->getPropertyKeys());


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
        $this->expectExceptionMessage('Not found');

        Notion::pages()->find('b55c9c91-384d-452b-81db-d1ef79372b79');
    }

    /** @test */
    public function it_assembles_properties_for_a_new_page() {

        $pageId = "0349b883a1c64539b435289ea62b6eab";
        $pageTitle = "I was updated from Tinkerwell";

        $checkboxKey = "CheckboxProperty";
        $checkboxValue = true;
        $dateRangeKey = "DateRangeProperty";
        $dateRangeStartValue = Carbon::now()->toDateTime();
        $dateRangeEndValue = Carbon::tomorrow()->toDateTime();
        $dateKey = "DateProperty";
        $dateValue = Carbon::yesterday()->toDateTime();

        $page = new Page();
        $page->setId($pageId);
        $page->setTitle("Name", $pageTitle);
        $page->setCheckbox($checkboxKey, $checkboxValue);
        $page->setDate($dateRangeKey, $dateRangeStartValue, $dateRangeEndValue);
        $page->setDate($dateKey, $dateValue);

        $properties = $page->getProperties();

        $this->assertEquals($page->getId(), $pageId);
        $this->assertEquals($page->getTitle(), $pageTitle);

        # checkbox
        $this->assertTrue(
            $this->assertContainsInstanceOf(Checkbox::class, $properties)
        );
        $checkboxProp = $page->getProperty($checkboxKey);
        $this->assertEquals($checkboxKey, $checkboxProp->getTitle());
        $checkboxContent = $checkboxProp->getRawContent();
        $this->assertArrayHasKey("checkbox", $checkboxContent);
        $this->assertEquals($checkboxContent["checkbox"], $checkboxValue);
        $this->assertEquals($checkboxProp->getContent(), $checkboxValue);
        $this->assertEquals($checkboxProp->asText(), $checkboxValue? "true" : "false");

        # date range
        $this->assertTrue(
            $this->assertContainsInstanceOf(Date::class, $properties)
        );
        $dateRangeProp = $page->getProperty($dateRangeKey);
        $this->assertEquals($dateRangeKey, $dateRangeProp->getTitle());
        $this->assertInstanceOf(RichDate::class, $dateRangeProp->getContent());
        $dateRangeContent = $dateRangeProp->getContent();
        $this->assertTrue($dateRangeProp->isRange());
        $this->assertEquals($dateRangeStartValue, $dateRangeProp->getStart());
        $this->assertEquals($dateRangeEndValue, $dateRangeProp->getEnd());

        # date
        $dateProp = $page->getProperty($dateKey);
        $this->assertEquals($dateKey, $dateProp->getTitle());
        $this->assertInstanceOf(RichDate::class, $dateProp->getContent());
        $dateContent = $dateProp->getContent();
        $this->assertFalse($dateProp->isRange());
        $this->assertEquals($dateValue, $dateProp->getStart());




    }


    // /** @test */
    // public function it_throws_a_handling_exception_not_implemented_for_create()
    // {

    //     $this->expectException(HandlingException::class);
    //     $this->expectExceptionMessage('Not implemented');

    //     Notion::pages()->create();
    // }

    // /** @test */
    // public function it_throws_a_handling_exception_not_implemented_for_update_properties()
    // {

    //     $this->expectException(HandlingException::class);
    //     $this->expectExceptionMessage('Not implemented');

    //     Notion::pages()->updateProperties();
    // }

}