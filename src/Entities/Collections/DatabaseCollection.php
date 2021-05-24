<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\Database;

/**
 * Class DatabaseCollection
 * @package FiveamCode\LaravelNotionApi\Entities\Collections
 */
class DatabaseCollection extends EntityCollection
{

    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $databaseChild) {
            $this->collection->add(new Database($databaseChild));
        }
    }
}
