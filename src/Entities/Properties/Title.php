<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

class Title extends Property
{
    protected string $plainText = "";

    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance("The property-type is title, however the raw data-structure does not represent this type (= array of items). Please check the raw response-data.");

        $this->fillText();
    }

    private function fillText(): void
    {
        $this->content = new RichText($this->rawContent);
        $this->plainText = $this->content->getPlaintext();
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
        return $this->plainText;
    }
}
