<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;


class BlockCollection extends EntityCollection
{
    protected function collectChildren() : void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $blockChild) {
            $this->collection->add(new Block($blockChild));
        }
    }
}
