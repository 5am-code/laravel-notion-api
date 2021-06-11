<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

use DateTime;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichDate;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class Date
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Date extends Property
{

    /**
     * @param $name
     * @return Select
     */
    public static function instance(?DateTime $start, ?DateTime $end = null): Date
    {
        $richDate = new RichDate();
        $richDate->setStart($start);
        $richDate->setEnd($end);

        $dateProperty = new Date();
        $dateProperty->content = $richDate;

        if ($richDate->isRange()) {
            $dateProperty->rawContent = [
                "date" => [
                    "start" => $start->format("c"),
                    "end" =>  $end->format("c")
                ]
            ];
        } else {
            $dateProperty->rawContent = [
                "date" => [
                    "start" => $start->format("c")
                ]
            ];
        }

        return $dateProperty;
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
    public function getContent(): RichDate
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isRange(): bool
    {
        return $this->getContent()->isRange();
    }

    /**
     * @return Date
     */
    public function getFrom(): Date
    {
        return $this->getContent()->getFrom();
    }

    /**
     * @return Date
     */
    public function getTo(): Date
    {
        return $this->getContent()->getTo();
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->content;
    }
}
