<?php

namespace FiveamCode\LaravelNotionApi\Entities\PropertyItems;

use DateTime;
use Illuminate\Support\Arr;
use FiveamCode\LaravelNotionApi\Entities\Entity;

/**
 * Class RichDate
 * @package FiveamCode\LaravelNotionApi\Entities\PropertyItems
 */
class RichDate extends Entity
{
    /**
     * @var string
     */
    protected DateTime $start;
    protected ?DateTime $end = null;


    /**
     * @param array $responseData
     */
    protected function setResponseData(array $responseData): void
    {
        $this->responseData = $responseData;
        $this->fillFromRaw();
    }

    /**
     *
     */
    protected function fillFromRaw(): void
    {
        $this->fillFrom();
        $this->fillTo();
    }

    /**
     *
     */
    protected function fillFrom(): void
    {
        if (Arr::exists($this->responseData, 'from')) {
            $this->from .= $this->responseData['from'];
        }
    }

    /**
     *
     */
    protected function fillTo(): void
    {
        if (Arr::exists($this->responseData, 'to')) {
            $this->from .= $this->responseData['to'];
        }
    }


    /**
     * @return bool
     */
    public function isRange(): bool
    {
        return $this->end != null;
    }

    /**
     * @return DateTime
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }


    /**
     * @return DateTime
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     *
     */
    public function setStart($start): void
    {
        $this->start = $start;
    }


    /**
     
     */
    public function setEnd($end): void
    {
        $this->end = $end;
    }
}
