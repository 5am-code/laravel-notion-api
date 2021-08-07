<?php

namespace FiveamCode\LaravelNotionApi\Entities\Contracts;

use FiveamCode\LaravelNotionApi\Entities\Properties\Property;

interface Modifiable
{
    public static function value($value): Property;
}