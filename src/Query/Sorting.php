<?php

namespace FiveamCode\LaravelNotionApi\Query;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use Illuminate\Support\Collection;

class Sorting extends QueryHelper
{

    private ?string $timestamp = null;
    private string $direction;

    public function __construct(string $direction, string $property = null, string $timestamp = null)
    {
        parent::__construct();

        if ($timestamp !== null && !$this->validTimestamps->contains($timestamp))
            throw WrapperException::instance(
                "Invalid sorting timestamp provided.", ["invalidTimestamp" => $timestamp]
            );


        if (!$this->validDirections->contains($direction))
            throw WrapperException::instance(
                "Invalid sorting direction provided.", ["invalidDirection" => $direction]
            );

        $this->property = $property;
        $this->timestamp = $timestamp;
        $this->direction = $direction;
    }

    public static function timestampSort(string $timestampToSort, string $direction): Sorting
    {
        $propertySort = new Sorting($direction, null, $timestampToSort);

        return $propertySort;
    }

    public static function propertySort(string $property, string $direction): Sorting
    {
        $propertySort = new Sorting($direction, $property);

        return $propertySort;
    }

    public function toArray(): array
    {
        if ($this->timestamp !== null) {
            return [
                "timestamp" => $this->timestamp,
                "direction" => $this->direction
            ];
        }

        return [
            "property" => $this->property,
            "direction" => $this->direction
        ];
    }

    public static function sortQuery(Collection $sortings): array
    {

        $querySortings = new Collection();

        $sortings->each(function (Sorting $sorting) use ($querySortings) {
            $querySortings->add($sorting->toArray());
        });

        return $querySortings->toArray();

    }


}