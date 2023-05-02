<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use Illuminate\Support\Collection;

/**
 * Class DatabaseSchemeBuilder.
 */
class DatabaseSchemeBuilder
{
    /**
     * @var Collection|null
     */
    private ?Collection $properties = null;

    /**
     * DatabaseSchemeBuilder constructor.
     */
    public function __construct()
    {
        $this->properties = collect();
    }

    /**
     * @param PropertyBuilder $builder
     * @return DatabaseSchemeBuilder
     */
    public function push(PropertyBuilder $builder): DatabaseSchemeBuilder
    {
        $this->properties->push($builder);

        return $this;
    }

    /**
     * Add raw property to the database scheme.
     * Please reference the Notion API documentation for the content array/object structure.
     * 
     * @param  string  $name
     * @param  string  $type
     * @param  array|object  $content
     * @return DatabaseSchemeBuilder
     */
    public function raw(string $name, string $type, array|object $content): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::raw($name, $type, $content));
    }

    /**
     * Add plain property to the database scheme.
     * For simply adding properties, without required content.
     * 
     * @param  string  $name
     * @param  string  $type
     * @return DatabaseSchemeBuilder
     */
    public function plain(string $name, string $type): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::plain($name, $type));
    }

    /**
     * @param string  $name
     * @return DatabaseSchemeBuilder
     */
    public function title(string $name = 'Name'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::title($name));
    }

    /**
     * @param string  $name
     * @return DatabaseSchemeBuilder
     */
    public function richText(string $name = 'Text'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::richText($name));
    }

    /**
     * @param string  $name
     * @return DatabaseSchemeBuilder
     */
    public function checkbox(string $name = 'Checkbox'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::checkbox($name));
    }

    /**
     * (currently not supported)
     * @todo increase Notion API Version, to make this work
     * 
     * @param string  $name
     * @return DatabaseSchemeBuilder
     */
    public function status(string $name = 'Status'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::status($name));
    }

    /**
     * @param string  $name
     * @return DatabaseSchemeBuilder
     */
    public function select(string $name, array $options): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::select($name, $options));
    }

    /**
     * @param string  $name
     * @param array  $options
     * @return DatabaseSchemeBuilder
     */
    public function multiSelect(string $name, array $options): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::multiSelect($name, $options));
    }

    /**
     * @param string $name
     * @param string $format
     * @return DatabaseSchemeBuilder
     */
    public function number(string $name = 'Number', string $format = 'number'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::number($name, $format));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function date(string $name = 'Date'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::date($name));
    }

    /**
     * @param string $name
     * @param string $databaseId
     * @return DatabaseSchemeBuilder
     */
    public function relation(string $name, string $databaseId): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::relation($name, $databaseId));
    }

    /**
     * @param string $name
     * @param string $expression
     * @return DatabaseSchemeBuilder
     */
    public function formula(string $name, string $expression): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::formula($name, $expression));
    }

    /**
     * @param string $name
     * @param string $rollupProperty
     * @param string $relationProperty
     * @param string $function
     * @return DatabaseSchemeBuilder
     */
    public function rollup(string $name, string $rollupProperty, string $relationProperty, string $function): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::rollup($name, $rollupProperty, $relationProperty, $function));
    }

    /**
     * @param string $name
     * @param string $rollupPropertyName
     * @param string $relationPropertyName
     * @param string $function
     * @return DatabaseSchemeBuilder
     */
    public function rollupByName(string $name, string $rollupPropertyName, string $relationPropertyName, string $function): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::rollupByName($name, $rollupPropertyName, $relationPropertyName, $function));
    }

    /**
     * @param string $name
     * @param string $rollupPropertyId
     * @param string $relationPropertyId
     * @param string $function
     * @return DatabaseSchemeBuilder
     */
    public function rollupById(string $name, string $rollupPropertyId, string $relationPropertyId, string $function): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::rollupById($name, $rollupPropertyId, $relationPropertyId, $function));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function url(string $name = 'Url'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::url($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function email(string $name = 'Email'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::email($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function phoneNumber(string $name = 'Phone Number'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::phoneNumber($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function people(string $name = 'People'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::people($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function files(string $name = 'Files'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::files($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function createdBy(string $name = 'Created By'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::createdBy($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function createdTime(string $name = 'Created Time'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::createdTime($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function lastEditedBy(string $name = 'Last Edited Time'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::lastEditedBy($name));
    }

    /**
     * @param string $name
     * @return DatabaseSchemeBuilder
     */
    public function lastEditedTime(string $name = 'Last Edited Time'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::lastEditedTime($name));
    }

    /**
     * @return Collection
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }
}
