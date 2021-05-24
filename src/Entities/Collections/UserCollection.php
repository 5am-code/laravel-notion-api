<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Entities\User;


/**
 * Class UserCollection
 * @package FiveamCode\LaravelNotionApi\Entities\Collections
 */
class UserCollection extends EntityCollection
{
    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $userChild) {
            $this->collection->add(new User($userChild));
        }
    }
}
