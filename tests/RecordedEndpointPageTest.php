<?php

use FiveamCode\LaravelNotionApi\Entities\Collections\EntityCollection;
use FiveamCode\LaravelNotionApi\Entities\Properties\Property;
use FiveamCode\LaravelNotionApi\Entities\Properties\Rollup;
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

    // dd($propertyKeys);

    expect($propertyKeys)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($propertyKeys)->toHaveCount(16);

    // dd($propertyKeys);

    foreach ($propertyKeys as $propertyKey) {
        $id = $databaseStructure->getProperty($propertyKey)->getId();
        $property = \Notion::page('f1884dca3885460e93f52bf4da7cce8e')->property($id);

        match ($propertyKey) {
            'Rollup' => dd($property->asCollection()) && expect($property->asCollection()->first())->toBeInstanceOf(Rollup::class),
            // default => throw new \Exception('Unknown property key')
            default => null
        };

        if ($propertyKey == 'Rollup' || $propertyKey == 'Person' || $propertyKey == 'Name') {
            expect($property)->toBeInstanceOf(EntityCollection::class);
        } else {
            expect($property)->toBeInstanceOf(Property::class);
        }
    }
});
