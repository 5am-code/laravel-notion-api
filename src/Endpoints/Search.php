<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Notion;

class Search extends Endpoint
{

    public function __construct(Notion $notion)
    {
        $this->notion = $notion;
    }

    // toDo: Think about it. 🧐
}