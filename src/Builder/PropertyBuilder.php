<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class PropertyBuilder.
 */
class PropertyBuilder
{
    public static function bulk(): DatabaseSchemeBuilder
    {
        return new DatabaseSchemeBuilder();
    }

    public static function raw(string $name, string $type, array|object $content): PropertyBuilder
    {
        return new PropertyBuilder($name, [
            'type' => $type,
            $type => $content,
        ]);
    }

    public static function plain(string $name, string $type): PropertyBuilder
    {
        return self::raw($name, $type, new \stdClass());
    }

    public static function title(string $name = 'Name'): PropertyBuilder
    {
        return self::plain($name, Property::TITLE);
    }

    public static function richText(string $name = 'Text'): PropertyBuilder
    {
        return self::plain($name, Property::RICH_TEXT);
    }

    public static function checkbox(string $name = 'Checkbox'): PropertyBuilder
    {
        return self::plain($name, Property::CHECKBOX);
    }

    public static function status(string $name): PropertyBuilder
    {
        return self::plain($name, Property::STATUS);
    }

    public static function select(string $name, array $options): PropertyBuilder
    {
        return self::raw($name, Property::SELECT, [
            'options' => $options,
        ]);
    }

    public static function multiSelect(string $name, array $options): PropertyBuilder
    {
        return self::raw($name, Property::MULTI_SELECT, [
            'options' => $options,
        ]);
    }

    public static function number(string $name = 'Number', $format = 'number'): PropertyBuilder
    {
        return self::raw($name, Property::NUMBER, [
            'format' => $format,
        ]);
    }

    public static function date(string $name = 'Date'): PropertyBuilder
    {
        return self::plain($name, Property::DATE);
    }

    public static function relation(string $name, string $databaseId): PropertyBuilder
    {
        return self::raw($name, Property::RELATION, [
            'database_id' => $databaseId,
        ]);
    }

    public static function formula(string $name, string $expression)
    {
        return self::raw($name, Property::FORMULA, [
            'expression' => $expression,
        ]);
    }

    public static function rollup(string $name, string $rollupProperty, string $relationProperty, string $function): PropertyBuilder
    {
        return self::rollupByName($name, $rollupProperty, $relationProperty, $function);
    }

    public static function rollupByName(string $name, string $rollupPropertyName, string $relationPropertyName, string $function): PropertyBuilder
    {
        return self::raw($name, Property::ROLLUP, [
            'relation_property_name' => $relationPropertyName,
            'rollup_property_name' => $rollupPropertyName,
            'function' => $function,
        ]);
    }

    public static function rollupById(string $name, string $rollupPropertyId, string $relationPropertyId, string $function): PropertyBuilder
    {
        return self::raw($name, Property::ROLLUP, [
            'relation_property_id' => $relationPropertyId,
            'rollup_property_id' => $rollupPropertyId,
            'function' => $function,
        ]);
    }

    public static function url(string $name = 'Url'): PropertyBuilder
    {
        return self::plain($name, Property::URL);
    }

    public static function email(string $name = 'Email'): PropertyBuilder
    {
        return self::plain($name, Property::EMAIL);
    }

    public static function phoneNumber(string $name = 'Phone Number'): PropertyBuilder
    {
        return self::plain($name, Property::PHONE_NUMBER);
    }

    public static function people(string $name = 'People'): PropertyBuilder
    {
        return self::plain($name, Property::PEOPLE);
    }

    public static function files(string $name = 'Files'): PropertyBuilder
    {
        return self::plain($name, Property::FILES);
    }

    public static function createdBy(string $name = 'Created By'): PropertyBuilder
    {
        return self::plain($name, Property::CREATED_BY);
    }

    public static function createdTime(string $name = 'Created Time'): PropertyBuilder
    {
        return self::plain($name, Property::CREATED_TIME);
    }

    public static function lastEditedBy(string $name = 'Last Edited By'): PropertyBuilder
    {
        return self::plain($name, Property::LAST_EDITED_BY);
    }

    public static function lastEditedTime(string $name = 'Last Edited Time'): PropertyBuilder
    {
        return self::plain($name, Property::LAST_EDITED_TIME);
    }

    private function __construct(private string $name, private array $payload)
    {
    }

    public function getName(): string
    {
        if ($this->name == '') {
            throw new HandlingException('Properties must have a name. No name given for the property structure:'.json_encode($this->payload));
        }

        return $this->name;
    }

    public function payload(): array
    {
        return $this->payload;
    }
}
