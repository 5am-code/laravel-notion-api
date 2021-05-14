<?php

namespace FiveamCode\LaravelNotionApi\Query;

class Filter extends QueryHelper
{

    public function __construct(string $property )
    {
        parent::__construct();

        $this->property = $property;


    }

//    public static function textFilter(string $property, ) {
//
//    }


}