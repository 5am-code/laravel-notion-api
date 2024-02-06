<?php

namespace FiveamCode\LaravelNotionApi\Entities\Collections;

use FiveamCode\LaravelNotionApi\Entities\User;
use Illuminate\Support\Collection;

/**
 * Class UserCollection.
 */
class UserCollection extends EntityCollection
{
    protected function collectChildren(): void
    {
        $this->collection = new Collection();
        foreach ($this->rawResults as $userChild) {
            //TODO: create a new type for 'people' (outer layer for user)
            if ($userChild['type'] === 'people') {
                $userChild = $userChild['people'];
            }

            $this->collection->add(new User($userChild));
        }
    }
}
