<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Entities\Properties\Property;

/**
 * Class PropertyBuilder.
 */
class PropertyBuilder
{
    public static function plain($type): PropertyBuilder
    {
        return new PropertyBuilder([
            'type' => $type,
            $type => new \stdClass(),
        ]);
    }

    public static function title(): PropertyBuilder
    {
        return self::plain(Property::TITLE);
    }

    public static function richText(): PropertyBuilder
    {
        return self::plain(Property::RICH_TEXT);
    }

    public static function number($format = 'number'): PropertyBuilder
    {
        return new PropertyBuilder([
            'type' => Property::NUMBER,
            Property::NUMBER => [
                'format' => $format,
            ],
        ]);
    }

    private function __construct(private $payload)
    {
    }

    public function payload(): array
    {
        return $this->payload;
    }
}
