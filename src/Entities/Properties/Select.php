<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\PropertyItems\SelectItem;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

class Select extends Property
{
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance("The property-type is select, however the raw data-structure does not reprecent this type. Please check the raw response-data.");

        $this->content = new SelectItem($this->rawContent);
    }

    public function getContent(): SelectItem
    {
        return $this->getItem();
    }

    public function getItem(): SelectItem
    {
        return $this->content;
    }

    public function getName()
    {
        return $this->content->getName();
    }

    public function getColor()
    {
        return $this->content->getColor();
    }
}
