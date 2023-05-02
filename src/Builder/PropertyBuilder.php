<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;

/**
 * Class PropertyBuilder.
 */
class PropertyBuilder
{
    /**
     * Create a new PropertyBuilder instance, for adding multiple properties at once.
     * 
     * @return DatabaseSchemeBuilder
     */
    public static function bulk(): DatabaseSchemeBuilder
    {
        return new DatabaseSchemeBuilder();
    }

    /**
     * Add raw property to the database scheme.
     * Please reference the Notion API documentation for the content array/object structure.
     * 
     * @param  string  $name
     * @param  string  $type
     * @param  array|object  $content
     * @return PropertyBuilder
     */
    public static function raw(string $name, string $type, array|object $content): PropertyBuilder
    {
        return new PropertyBuilder($name, [
            'type' => $type,
            $type => $content,
        ]);
    }

    /**
     * Add plain property to the database scheme.
     * For simply adding properties, without required content.
     * 
     * @param  string  $name
     * @param  string  $type
     * @return PropertyBuilder
     */
    public static function plain(string $name, string $type): PropertyBuilder
    {
        return self::raw($name, $type, new \stdClass());
    }

    /**
     * @param string  $name
     * @return PropertyBuilder
     */
    public static function title(string $name = 'Name'): PropertyBuilder
    {
        return self::plain($name, Property::TITLE);
    }

    /**
     * @param string  $name
     * @return PropertyBuilder
     */
    public static function richText(string $name = 'Text'): PropertyBuilder
    {
        return self::plain($name, Property::RICH_TEXT);
    }

    /**
     * @param string  $name
     * @return PropertyBuilder
     */
    public static function checkbox(string $name = 'Checkbox'): PropertyBuilder
    {
        return self::plain($name, Property::CHECKBOX);
    }

    /**
     * (currently not supported)
     * @todo increase Notion API Version, to make this work
     * 
     * @param string  $name
     * @return PropertyBuilder
     */
    public static function status(string $name = 'Status'): PropertyBuilder
    {
        return self::plain($name, Property::STATUS);
    }

    /**
     * @param string  $name
     * @param array $options
     * @return PropertyBuilder
     */
    public static function select(string $name, array $options): PropertyBuilder
    {
        return self::raw($name, Property::SELECT, [
            'options' => $options,
        ]);
    }

    /**
     * @param string  $name
     * @param array  $options
     * @return PropertyBuilder
     */
    public static function multiSelect(string $name, array $options): PropertyBuilder
    {
        return self::raw($name, Property::MULTI_SELECT, [
            'options' => $options,
        ]);
    }

    /**
     * @param string  $name
     * @param string  $format
     * @return PropertyBuilder
     */
    public static function number(string $name = 'Number', $format = 'number'): PropertyBuilder
    {
        return self::raw($name, Property::NUMBER, [
            'format' => $format,
        ]);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function date(string $name = 'Date'): PropertyBuilder
    {
        return self::plain($name, Property::DATE);
    }

    /**
     * @param string $name
     * @param string $databaseId
     * @return PropertyBuilder
     */
    public static function relation(string $name, string $databaseId): PropertyBuilder
    {
        return self::raw($name, Property::RELATION, [
            'database_id' => $databaseId,
        ]);
    }

    /**
     * @param string $name
     * @param string $expression
     * @return PropertyBuilder
     */
    public static function formula(string $name, string $expression)
    {
        return self::raw($name, Property::FORMULA, [
            'expression' => $expression,
        ]);
    }

    /**
     * @param string $name
     * @param string $rollupProperty
     * @param string $relationProperty
     * @param string $function
     * @return PropertyBuilder
     */
    public static function rollup(string $name, string $rollupProperty, string $relationProperty, string $function): PropertyBuilder
    {
        return self::rollupByName($name, $rollupProperty, $relationProperty, $function);
    }

    /**
     * @param string $name
     * @param string $rollupPropertyName
     * @param string $relationPropertyName
     * @param string $function
     * @return PropertyBuilder
     */
    public static function rollupByName(string $name, string $rollupPropertyName, string $relationPropertyName, string $function): PropertyBuilder
    {
        return self::raw($name, Property::ROLLUP, [
            'relation_property_name' => $relationPropertyName,
            'rollup_property_name' => $rollupPropertyName,
            'function' => $function,
        ]);
    }

    /**
     * @param string $name
     * @param string $rollupPropertyId
     * @param string $relationPropertyId
     * @param string $function
     * @return PropertyBuilder
     */
    public static function rollupById(string $name, string $rollupPropertyId, string $relationPropertyId, string $function): PropertyBuilder
    {
        return self::raw($name, Property::ROLLUP, [
            'relation_property_id' => $relationPropertyId,
            'rollup_property_id' => $rollupPropertyId,
            'function' => $function,
        ]);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function url(string $name = 'Url'): PropertyBuilder
    {
        return self::plain($name, Property::URL);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function email(string $name = 'Email'): PropertyBuilder
    {
        return self::plain($name, Property::EMAIL);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function phoneNumber(string $name = 'Phone Number'): PropertyBuilder
    {
        return self::plain($name, Property::PHONE_NUMBER);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function people(string $name = 'People'): PropertyBuilder
    {
        return self::plain($name, Property::PEOPLE);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function files(string $name = 'Files'): PropertyBuilder
    {
        return self::plain($name, Property::FILES);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function createdBy(string $name = 'Created By'): PropertyBuilder
    {
        return self::plain($name, Property::CREATED_BY);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function createdTime(string $name = 'Created Time'): PropertyBuilder
    {
        return self::plain($name, Property::CREATED_TIME);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function lastEditedBy(string $name = 'Last Edited By'): PropertyBuilder
    {
        return self::plain($name, Property::LAST_EDITED_BY);
    }

    /**
     * @param string $name
     * @return PropertyBuilder
     */
    public static function lastEditedTime(string $name = 'Last Edited Time'): PropertyBuilder
    {
        return self::plain($name, Property::LAST_EDITED_TIME);
    }

    /**
     * @param string $name
     * @param array $payload
     */
    private function __construct(private string $name, private array $payload)
    {
    }

    /**
     * @return string
     * @throws HandlingException
     */
    public function getName(): string
    {
        if ($this->name == '') {
            throw new HandlingException('Properties must have a name. No name given for the property structure:' . json_encode($this->payload));
        }

        return $this->name;
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
