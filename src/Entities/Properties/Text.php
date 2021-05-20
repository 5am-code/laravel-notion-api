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

class Text extends Property
{
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance("The property-type is text, however the raw data-structure does not represent this type (= array of items). Please check the raw response-data.");

        $this->content = new RichText($this->rawContent);
    }

    public function getContent(): RichText
    {
        return $this->getRichText();
    }

    public function getRichText(): RichText
    {
        return $this->content;
    }

    public function getPlainText()
    {
        return $this->content->getPlaintext();
    }

}
