<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use FiveamCode\LaravelNotionApi\Entities\User;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

class CreatedBy extends Property
{
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        if (!is_array($this->rawContent))
            throw HandlingException::instance("The property-type is created_by, however the raw data-structure does not reprecent this type (= array of items). Please check the raw response-data.");

        $this->content = new User($this->rawContent);
    }

    public function getContent(): User
    {
        return $this->getUser();
    }

    public function getUser(): User
    {
        return $this->content;
    }

}
