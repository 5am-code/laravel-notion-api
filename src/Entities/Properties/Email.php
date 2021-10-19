<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\Contracts\Modifiable;

/**
 * Class Email
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Email extends Property implements Modifiable
{
    public function __construct(string $title = null){
        parent::__construct($title);
        $this->type = "email";
    }

    /**
     * @param $email
     * @return Email
     */
    public static function value(string $email): Email
    {
        $emailProperty = new Email();
        $emailProperty->content = $email;

        $emailProperty->rawContent = $email;

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
