<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\Contracts\Modifiable;

/**
 * Class PhoneNumber
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class PhoneNumber extends Property implements Modifiable
{
    
    public function __construct(string $title = null){
        parent::__construct($title);
        $this->type = "phone_number";
    }

    /**
     * @param $phoneNumber
     * @return PhoneNumber
     */
    public static function value(string $phoneNumber): PhoneNumber
    {
        $urlProperty = new PhoneNumber();
        $urlProperty->content = $phoneNumber;

        $urlProperty->rawContent = $phoneNumber;

        return $urlProperty;
    }


    /**
     *
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->fillPhoneNumber();
    }

    /**
     *
     */
    protected function fillPhoneNumber(): void
    {
        $this->content = $this->rawContent;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->content;
    }
}
