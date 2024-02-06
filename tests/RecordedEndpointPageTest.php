<?php

use Carbon\Carbon;
use FiveamCode\LaravelNotionApi\Entities\Collections\CommentCollection;
use FiveamCode\LaravelNotionApi\Entities\Collections\EntityCollection;
use FiveamCode\LaravelNotionApi\Entities\Comment;
use FiveamCode\LaravelNotionApi\Entities\Entity;
use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use Illuminate\Support\Facades\Http;

$httpRecorder = null;

beforeEach(function () {
    $this->httpRecorder = Http::recordAndFakeLater([
        'https://api.notion.com/v1/pages*',
        'https://api.notion.com/v1/databases*',
    ])->storeIn('snapshots/page-property-items');
});

it('should fetch specific property items of a page', function () {
    $this->httpRecorder->nameForNextRequest('database-for-properties');
    $databaseStructure = \Notion::databases()->find('cdd4befe814144f7b1eecb9c123bd4fb');

    $propertyKeys = $databaseStructure->getProperties()->map(fn ($o) => $o->getTitle());

    foreach ($propertyKeys as $propertyKey) {
        $id = $databaseStructure->getProperty($propertyKey)->getId();
        $property = \Notion::page('f1884dca3885460e93f52bf4da7cce8e')->property($id);

        if ($propertyKey == 'Rollup' || $propertyKey == 'Person' || $propertyKey == 'Name') {
            expect($property)->toBeInstanceOf(EntityCollection::class);
        } else {
            expect($property)->toBeInstanceOf(Property::class);
        }
    }
});
