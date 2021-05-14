<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Entity;

interface EndpointInterface
{
    public function find(string $id): Entity;
}
