<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;

class Block extends Entity
{
    protected function setRaw(array $raw): void
    {
        parent::setRaw($raw);
        if ($raw['object'] !== 'block') throw WrapperException::instance("invalid json-array: the given object is not a block");
    }
}
