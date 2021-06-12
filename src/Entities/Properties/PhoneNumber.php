<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

/**
 * Class PhoneNumber
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class PhoneNumber extends Property
{
    /**
     * @param $phoneNumber
     * @return PhoneNumber
     */
    public static function instance(string $phoneNumber): PhoneNumber
    {
        $urlProperty = new PhoneNumber();
        $urlProperty->content = $phoneNumber;

        $urlProperty->rawContent = [
            "phone_number" => $phoneNumber
        ];

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