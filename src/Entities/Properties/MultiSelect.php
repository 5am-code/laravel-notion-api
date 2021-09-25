<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\Contracts\Modifiable;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\SelectItem;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Collection;

/**
 * Class MultiSelect
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class MultiSelect extends Property implements Modifiable
{
    /**
     * @param $names
     * @return MultiSelect
     */
    public static function value(array $names): MultiSelect
    {
        $multiSelectProperty = new MultiSelect();
        $multiSelectRawContent = [];
        $selectItemCollection = new Collection();

        foreach ($names as $name) {
            $selectItem = new SelectItem();
            $selectItem->setName($name);
            $selectItemCollection->add($selectItem);
            array_push($multiSelectRawContent, [
                "name" => $selectItem->getName()
            ]);
        }

        $multiSelectProperty->content = $selectItemCollection;
        $multiSelectProperty->rawContent = [
            "multi_select" => $multiSelectRawContent
        ];

        return $multiSelectProperty;
    }

    /**
     * @throws HandlingException
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance('The property-type is multi_select, however the raw data-structure does not represent this type (= array of items). Please check the raw response-data.');

        $itemCollection = new Collection();

        foreach ($this->rawContent as $item) {
            $itemCollection->add(new SelectItem($item));
        }

        $this->content = $itemCollection;
    }

    /**
     * @return Collection
     */
    public function getContent(): Collection
    {
        return $this->getItems();
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        $names = [];
        foreach ($this->getItems() as $item) {
            array_push($names, $item->getName());
        }
        return $names;
    }

    /**
     * @return array
     */
    public function getColors(): array
    {
        $colors = [];
        foreach ($this->getItems() as $item) {
            array_push($colors, $item->getColor());
        }
        return $colors;
    }
}
