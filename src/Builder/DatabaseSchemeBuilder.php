<?php

namespace FiveamCode\LaravelNotionApi\Builder;

use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use Illuminate\Support\Collection;

/**
 * Class DatabaseSchemeBuilder.
 */
class DatabaseSchemeBuilder
{
    private ?Collection $properties = null;

    public function __construct()
    {
        $this->properties = collect();
    }

    public function push(PropertyBuilder $builder): DatabaseSchemeBuilder
    {
        $this->properties->push($builder);
        return $this;
    }

    public function raw(string $name, string $type, array|object $content): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::raw($name, $type, $content));
    }

    public function plain(string $name, string $type): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::plain($name, $type));
    }

    public function title(string $name = 'Name'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::title($name));
    }

    public function richText(string $name = 'Text'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::richText($name));
    }

    public function checkbox(string $name = 'Checkbox'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::checkbox($name));
    }

    public function status(string $name = 'Status'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::status($name));
    }

    public function select(string $name, array $options): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::select($name, $options));
    }

    public function multiSelect(string $name, array $options): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::multiSelect($name, $options));
    }

    public function number(string $name = 'Number', $format = 'number'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::number($name, $format));
    }

    public function date(string $name = 'Date'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::date($name));
    }

    public function relation(string $name, string $databaseId): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::relation($name, $databaseId));
    }

    public function formula(string $name, string $expression): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::formula($name, $expression));
    }

    public function rollup(string $name, string $rollupProperty, string $relationProperty, string $function): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::rollup($name, $rollupProperty, $relationProperty, $function));
    }

    public function rollupByName(string $name, string $rollupPropertyName, string $relationPropertyName, string $function): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::rollupByName($name, $rollupPropertyName, $relationPropertyName, $function));
    }

    public function rollupById(string $name, string $rollupPropertyId, string $relationPropertyId, string $function): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::rollupById($name, $rollupPropertyId, $relationPropertyId, $function));
    }

    public function url(string $name = 'Url'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::url($name));
    }

    public function email(string $name = 'Email'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::email($name));
    }

    public function phoneNumber(string $name = 'Phone Number'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::phoneNumber($name));
    }

    public function people(string $name = 'People'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::people($name));
    }

    public function files(string $name = 'Files'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::files($name));
    }

    public function createdBy(string $name = 'Created By'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::createdBy($name));
    }

    public function createdTime(string $name = 'Created Time'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::createdTime($name));
    }

    public function lastEditedBy(string $name = 'Last Edited Time'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::lastEditedBy($name));
    }

    public function lastEditedTime(string $name = 'Last Edited Time'): DatabaseSchemeBuilder
    {
        return $this->push(PropertyBuilder::lastEditedTime($name));
    }

    public function getProperties(): Collection
    {
        return $this->properties;
    }
}
