<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use DateTime;
use Exception;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class CreatedTime
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class CreatedTime extends Property
{

    public function __construct(string $title = null){
        parent::__construct($title);
        $this->type = "created_time";
    }

    /**
     * @throws HandlingException
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();

        try {
            if ($this->rawContent != null) {
                $this->content = new DateTime($this->rawContent);
            }
        } catch (Exception $e) {
            throw HandlingException::instance('The content of created_time is not a valid ISO 8601 date time string.');
        }
    }


    /**
     * @return DateTime
     */
    public function getContent(): DateTime
    {
        return $this->content;
    }
}
