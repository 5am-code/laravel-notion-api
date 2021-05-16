<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use FiveamCode\LaravelNotionApi\Entities\Database;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class DatabaseCollection extends EntityCollection
{
    protected function collectChildren()
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $databaseChild) {
            $this->collection->add(new Database($databaseChild));
        }
    }
}
