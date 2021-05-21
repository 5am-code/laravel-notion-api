<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\SelectItem;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Number extends Property
{
    protected float $number = 0;

    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        // if (!is_array($this->rawContent))
        //     throw HandlingException::instance("The property-type is number, however the raw data-structure does not represent this type (= array of items). Please check the raw response-data.");

        $this->fillNumber();
    }

    protected function fillNumber(): void
    {
        $this->content = $this->rawContent;
    }

    public function getContent(): float
    {
        return $this->content;
    }

    public function getNumber(): float
    {
        return $this->content;
    }
}
