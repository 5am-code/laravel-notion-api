<?php

namespace FiveamCode\LaravelNotionApi\Entities\Properties;

class Number extends Property
{
    protected float $number = 0;

    protected function fillFromRaw(): void
    {
        parent::fillFromRaw();
        $this->fillNumber();
    }

    protected function fillNumber(): void
    {
        $this->content = $this->rawContent;
    }

    public function getContent(): float
    {
        return $this->content;
    }

    public function getNumber(): float
    {
        return $this->content;
    }
}
