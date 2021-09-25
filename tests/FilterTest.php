<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use Notion;
use Illuminate\Support\Facades\Http;

/**
 * Class HandlingExceptionTest
 * @package FiveamCode\LaravelNotionApi\Tests
 */
class FilterTest extends NotionApiTest
{

    /** @test */
    public function it_creates_a_text_filter_with_the_given_data()
    {
        $filter = Filter::textFilter("Name", Operators::EQUALS, "Ada Lovelace");

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertArrayHasKey("property", $filter->toQuery());
        $this->assertEquals("Name", $filter->toQuery()["property"]);
        $this->assertArrayHasKey("text", $filter->toQuery());
        $this->assertArrayHasKey("equals", $filter->toQuery()["text"]);
        $this->assertEquals("Ada Lovelace", $filter->toQuery()["text"]["equals"]);
    }

    /** @test */
    public function it_creates_a_number_filter_with_the_given_data()
    {
        $filter = Filter::numberFilter("Awesomeness Level", Operators::GREATER_THAN_OR_EQUAL_TO, 9000);

        $this->assertInstanceOf(Filter::class, $filter);
        $this->assertArrayHasKey("property", $filter->toQuery());
        $this->assertEquals("Awesomeness Level", $filter->toQuery()["property"]);
        $this->assertArrayHasKey("number", $filter->toQuery());
        $this->assertArrayHasKey("greater_than_or_equal_to", $filter->toQuery()["number"]);
        $this->assertEquals("9000", $filter->toQuery()["number"]["greater_than_or_equal_to"]);
    }

    /** @test */
    public function it_throws_an_exception_for_an_invalid_comparison_operator()
    {
        $this->expectException(HandlingException::class);
        $this->expectExceptionMessage("Invalid comparison operator");
        $filter = Filter::numberFilter("Awesomeness Level", "non_existing_operator", 9000);
    }

    /** @test */
    public function it_throws_an_exception_for_an_invalid_filter_definition() {
        $filter = new Filter("Test");

        $this->expectException(HandlingException::class);
        $this->expectExceptionMessage("Invalid filter definition.");
        $filter->toArray();
    }

    /** @test */
    public function it_throws_an_exception_for_an_invalid_filter_type() {
        $filter = new Filter("Test", "non_existing_filter_type");

//        $this->expectException(HandlingException::class);
//        $this->expectExceptionMessage("Invalid filter definition.");
        $filter->toArray();
    }
}