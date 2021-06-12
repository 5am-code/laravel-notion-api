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
     * @param $start
     * @param $end
     * @return Date
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
        $this->fillDate();
    }

    protected function fillDate(): void
    {
        $richDate = new RichDate();

        if (isset($this->rawContent["start"])) {
            $startAsIsoString = $this->rawContent["start"];
            $richDate->setStart(new DateTime($startAsIsoString));
        }

        
        if (isset($this->rawContent["end"])) {
            $endAsIsoString = $this->rawContent["end"];
            $richDate->setEnd(new DateTime($endAsIsoString));
        }

        $this->content = $richDate;
    }

    /**
     * @return RichDate
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
     * @return DateTime
     */
    public function getStart(): DateTime
    {
        return $this->getContent()->getStart();
    }

    /**
     * @return DateTime
     */
    public function getEnd(): DateTime
    {
        return $this->getContent()->getEnd();
    }
}
