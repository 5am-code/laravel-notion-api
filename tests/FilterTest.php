<?php


use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;

it('creates a text filter with the given data', function () {
    $filter = Filter::textFilter('Name', Operators::EQUALS, 'Ada Lovelace');

    $this->assertInstanceOf(Filter::class, $filter);
    $this->assertArrayHasKey('property', $filter->toQuery());
    $this->assertEquals('Name', $filter->toQuery()['property']);
    $this->assertArrayHasKey('text', $filter->toQuery());
    $this->assertArrayHasKey('equals', $filter->toQuery()['text']);
    $this->assertEquals('Ada Lovelace', $filter->toQuery()['text']['equals']);#
});


it('creates a number filter with the given data', function () {
    $filter = Filter::numberFilter('Awesomeness Level', Operators::GREATER_THAN_OR_EQUAL_TO, 9000);

    $this->assertInstanceOf(Filter::class, $filter);
    $this->assertArrayHasKey('property', $filter->toQuery());
    $this->assertEquals('Awesomeness Level', $filter->toQuery()['property']);
    $this->assertArrayHasKey('number', $filter->toQuery());
    $this->assertArrayHasKey('greater_than_or_equal_to', $filter->toQuery()['number']);
    $this->assertEquals('9000', $filter->toQuery()['number']['greater_than_or_equal_to']);
});

it('throws an HandlingException for an invalid comparison operator', function () {
    $this->expectException(HandlingException::class);
    $this->expectExceptionMessage('Invalid comparison operator');
    $filter = Filter::numberFilter('Awesomeness Level', 'non_existing_operator', 9000);
});

it('throws an exception for an invalid filter definition', function () {
    $filter = new Filter('Test');

    $this->expectException(HandlingException::class);
    $this->expectExceptionMessage('Invalid filter definition.');
    $filter->toArray();
});