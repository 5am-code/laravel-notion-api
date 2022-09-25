<?php

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\FilterBag;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;

it('creates a FilterBag with an "or" operator with the instance method', function () {
    $filterBag = FilterBag::or();

    $this->assertInstanceOf(FilterBag::class, $filterBag);

    $queryFilter = $filterBag->toQuery();

    $this->assertArrayHasKey('or', $queryFilter);
});

it('creates a FilterBag with an "and" operator with the instance method', function () {
    $filterBag = FilterBag::and();

    $this->assertInstanceOf(FilterBag::class, $filterBag);

    $queryFilter = $filterBag->toQuery();

    $this->assertArrayHasKey('and', $queryFilter);
});

it('throws an exception when providing an invalid operator', function () {
    $this->expectException(HandlingException::class);
    $this->expectExceptionMessage('Invalid operator for FilterBag: invalid');

    new FilterBag('invalid');
});

it('only allows the nesting of FilterBags up to two levels', function () {
    $this->expectException(HandlingException::class);
    $this->expectExceptionMessage('The maximum nesting level of compound filters must not exceed 2.');

    $filterBag = new FilterBag('and');

    $filterBag->addFilter(
        Filter::rawFilter('Known for', [
            'multi_select' => ['contains' => 'UNIVAC'],
        ])
    );

    $nameFilterBag = new FilterBag('or');
    $nameFilterBag
        ->addFilter(Filter::textFilter('Name', Operators::CONTAINS, 'Grace'))
        ->addFilter(Filter::textFilter('Name', Operators::CONTAINS, 'Jean'));

    $anotherBag = new FilterBag();
    $nameFilterBag->addFilterBag($anotherBag);

    $filterBag->addFilterBag($nameFilterBag);
});

it('allows the nesting of multiple FilterBags inside one FilterBag', function () {
    // TODO
});

it('creates the correct query structure for a nested FilterBag', function () {
    // TODO
});

it('creates the correct query structure for a FilterBag with one level', function () {
    // TODO
});
