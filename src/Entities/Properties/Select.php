<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\SelectItem;

/**
 * Class Select
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Select extends Property
{
    /**
     * @throws HandlingException
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance('The property-type is select, however the raw data-structure does not reprecent this type. Please check the raw response-data.');

        $this->content = new SelectItem($this->rawContent);
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
