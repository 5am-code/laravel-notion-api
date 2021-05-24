<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;

/**
 * Class Title
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Title extends Property
{
    /**
     * @var string
     */
    protected string $plainText = '';

    /**
     * @throws HandlingException
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance('The property-type is title, however the raw data-structure does not represent this type (= array of items). Please check the raw response-data.');

        $this->fillText();
    }

    /**
     *
     */
    private function fillText(): void
    {
        $this->content = new RichText($this->rawContent);
        $this->plainText = $this->content->getPlainText();
    }

    /**
     * @return RichText
     */
    public function getContent(): RichText
    {
        return $this->getRichText();
    }

    /**
     * @return RichText
     */
    public function getRichText(): RichText
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPlainText(): string
    {
        return $this->plainText;
    }
}
