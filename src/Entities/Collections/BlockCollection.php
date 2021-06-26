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
    private bool $showUnsupported = false;

    public function withUnsupported(): BlockCollection
    {
        $this->showUnsupported = true;
        return $this;
    }

    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $blockChildContent) {
            $this->collection->add(Block::fromResponse($blockChildContent));
        }
    }

    public function asCollection(): Collection
    {
        $collection =parent::asCollection();
        if ($this->showUnsupported) {
            return $collection;
        } else {
            return $collection->filter(function($block){
                return $block->getType() != 'unsupported';
            });
        }
    }

    public function asTextCollection(): Collection
    {
        $textCollection = new Collection();
        foreach ($this->asCollection() as $block) {
            $textCollection->add($block->asText());
        }
        return $textCollection;
    }
}
