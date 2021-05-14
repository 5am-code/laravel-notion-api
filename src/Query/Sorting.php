<?php

namespace FiveamCode\LaravelNotionApi\Query;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;

class Sorting extends QueryHelper
{

    private string $timestamp;
    private string $direction;

    public function __construct(string $timestamp, string $direction)
    {
        parent::__construct();

        if ($this->validTimestamps->contains($timestamp))
            throw WrapperException::instance(
                "Invalid sorting timestamp provided.", ["invalidTimestamp" => $timestamp]
            );

        if ($this->validDirections->contains($direction))
            throw WrapperException::instance(
                "Invalid sorting direction provided.", ["invalidDirection" => $direction]
            );

        $this->timestamp = $timestamp;
        $this->direction = $direction;
    }




}