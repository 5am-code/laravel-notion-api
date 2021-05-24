<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\Page;


/**
 * Class PageCollection
 * @package FiveamCode\LaravelNotionApi\Entities\Collections
 */
class PageCollection extends EntityCollection
{

    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $pageChild) {
            $this->collection->add(new Page($pageChild));
        }
    }
}
