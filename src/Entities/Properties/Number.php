<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

/**
 * Class Number
 * @package FiveamCode\LaravelNotionApi\Entities\Properties
 */
class Number extends Property
{
    /**
     * @var float|int
     */
    protected float $number = 0;

    /**
     *
     */
    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->fillNumber();
    }

    /**
     *
     */
    protected function fillNumber(): void
    {
        $this->content = $this->rawContent;
    }

    /**
     * @return float
     */
    public function getContent(): float
    {
        return $this->content;
    }

    /**
     * @return float
     */
    public function getNumber(): float
    {
        return $this->content;
    }
}
