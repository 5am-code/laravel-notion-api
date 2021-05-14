<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;

class User extends Entity
{
    protected function setRaw(array $raw): void
    {
        parent::setRaw($raw);
        if ($raw['object'] != 'user') throw WrapperException::instance("invalid json-array: the given object is not a user");
    }
}
