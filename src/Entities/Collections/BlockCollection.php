<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;


/**
 * Class BlockCollection
 * @package FiveamCode\LaravelNotionApi\Entities\Collections
 */
class BlockCollection extends EntityCollection
{
    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $blockChild) {
            $this->collection->add(new Block($blockChild));
        }
    }
}
