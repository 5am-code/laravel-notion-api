<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use FiveamCode\LaravelNotionApi\Entities\User;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class UserCollection extends EntityCollection
{
    protected function collectChildren() : void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $userChild) {
            $this->collection->add(new User($userChild));
        }
    }
}
