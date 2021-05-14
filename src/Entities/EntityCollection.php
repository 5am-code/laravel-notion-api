<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;

//TODO:WORK IN PROGRESS
class EntityCollection
{
    protected string $raw;
    protected Notion $notion;

    public function __construct(Notion $notion = null, string $raw = null)
    {
        $this->notion = $notion;
        $this->setRaw($raw);
    }

    protected function setRaw(string $raw):void{
        if (!isset($raw['object'])) throw WrapperException::instance("invalid json-array: no object given");
        if (!isset($raw['results'])) throw WrapperException::instance("invalid json-array: no results given");
        if ($raw['object'] != 'list') throw WrapperException::instance("invalid json-array: the given object is not a list");

        $this->raw = $raw;

    }

    public function getRaw(){
        return $this->raw;
    }
}
