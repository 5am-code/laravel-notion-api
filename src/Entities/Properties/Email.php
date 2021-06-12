<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

/**
 * Class Email
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Email extends Property
{
    /**
     * @param $email
     * @return Email
     */
    public static function instance(string $email): Email
    {
        $emailProperty = new Email();
        $emailProperty->content = $email;

        $emailProperty->rawContent = [
            "email" => $email
        ];

        return $emailProperty;
    }


    /**
     *
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->fillEmail();
    }

    /**
     *
     */
    protected function fillEmail(): void
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
    public function getEmail(): string
    {
        return $this->content;
    }
}
