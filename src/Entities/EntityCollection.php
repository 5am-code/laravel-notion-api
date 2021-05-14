<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;


//TODO:WORK IN PROGRESS
class EntityCollection
{
    protected array $raw;
    protected Notion $notion;

    public function __construct(Notion $notion = null, array $raw = null)
    {
        $this->notion = $notion;
        $this->setRaw($raw);
    }

    protected function setRaw(array $raw): void
    {
        if (!Arr::exists($raw, 'object')) throw WrapperException::instance("invalid json-array: no object given");
        if (!Arr::exists($raw, 'results')) throw WrapperException::instance("invalid json-array: no results given");
        if ($raw['object'] != 'list') throw WrapperException::instance("invalid json-array: the given object is not a list");

        $this->raw = $raw;
    }

    public function getRaw(): array
    {
        return $this->raw;
    }
}
