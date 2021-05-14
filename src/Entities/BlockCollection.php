<?php

namespace FiveamCode\LaravelNotionApi\Entities;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class BlockCollection extends EntityCollection
{

    protected function setResponseData(array $responseData): void
    {
        parent::setResponseData($responseData);
        $this->collectChildren();
    }

    protected function collectChildren()
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $blockChild) {
            $this->collection->add(new Block($blockChild));
        }
    }
}
