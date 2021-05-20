<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\SelectItem;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MultiSelect extends Property
{
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance("The property-type is multi_select, however the raw data-structure does not represent this type (= array of items). Please check the raw response-data.");

        $itemCollection = new Collection();

        foreach ($this->rawContent as $item) {
            $itemCollection->add(new SelectItem($item));
        }

        $this->content = $itemCollection;
    }

    public function getContent(): Collection
    {
        return $this->getItems();
    }

    public function getItems(): Collection
    {
        return $this->content;
    }

    public function getNames(): array
    {
        $names = [];
        foreach ($this->getItems() as $item) {
            array_push($names, $item->getName());
        }
        return $names;
    }

    public function getColors(): array
    {
        $colors = [];
        foreach ($this->getItems() as $item) {
            array_push($colors, $item->getColor());
        }
        return $colors;
    }
}
