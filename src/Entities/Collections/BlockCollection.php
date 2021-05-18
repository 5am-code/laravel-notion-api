<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use FiveamCode\LaravelNotionApi\Entities\Blocks\Block;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


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
