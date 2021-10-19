<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\Contracts\Modifiable;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\SelectItem;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Select
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Select extends Property implements Modifiable
{
    /**
     * @var Collection 
     */
    private Collection $options;

    
    public function __construct(string $title = null){
        parent::__construct($title);
        $this->type = "select";
    }

    /**
     * @param $name
     * @return Select
     */
    public static function value(string $name): Select
    {
        $selectProperty = new Select();

        $selectItem = new SelectItem();
        $selectItem->setName($name);
        $selectProperty->content = $selectItem;

        $selectProperty->rawContent = [
            "name" => $selectItem->getName()
        ];

        return $selectProperty;
    }

    /**
     * @throws HandlingException
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance('The property-type is select, however the raw data-structure does not reprecent this type. Please check the raw response-data.');

        if (array_key_exists('options', $this->rawContent)) {
            $this->options = new Collection();
            foreach ($this->rawContent['options'] as $key => $item) {
                $this->options->add(new SelectItem($item));
            }
        } else {
            foreach ($this->rawContent as $key => $item) {
                $this->content = new SelectItem($this->rawContent);
            }
        }
    }

    /**
     * @return SelectItem
     */
    public function getContent(): SelectItem
    {
        return $this->getItem();
    }

    /**
     * @return SelectItem
     */
    public function getItem(): SelectItem
    {
        return $this->content;
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->content->getName();
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->content->getColor();
    }
}
