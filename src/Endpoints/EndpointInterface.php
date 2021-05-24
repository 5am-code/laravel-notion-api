<?php

namespace FiveamCode\LaravelNotionApi\Endpoints;

use FiveamCode\LaravelNotionApi\Entities\Entity;

/**
 * Interface EndpointInterface
 * @package FiveamCode\LaravelNotionApi\Endpoints
 */
interface EndpointInterface
{
    /**
     * @param string $id
     * @return Entity
     */
    public function find(string $id): Entity;
}
