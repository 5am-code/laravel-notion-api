<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Checkbox
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Checkbox extends Property
{

    /**
     * @param $checked
     * @return Checkbox
     */
    public static function instance(bool $checked): Checkbox
    {
        $checkboxProperty = new Checkbox();
        $checkboxProperty->content = $checked;

        $checkboxProperty->rawContent = [
            "checkbox" => $checkboxProperty->isChecked()
        ];

        return $checkboxProperty;
    }

    /**
     * @throws HandlingException
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->content = $this->rawContent;
    }

    /**
     * @return bool
     */
    public function getContent(): bool
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function asText(): string{
        return ($this->getContent()) ? "true" : "false";
    }
}
