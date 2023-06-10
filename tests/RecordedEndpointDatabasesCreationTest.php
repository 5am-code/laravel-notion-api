<?php

use FiveamCode\LaravelNotionApi\Builder\PropertyBuilder;
use FiveamCode\LaravelNotionApi\Entities\Properties\Checkbox;
use FiveamCode\LaravelNotionApi\Entities\Properties\CreatedBy;
use FiveamCode\LaravelNotionApi\Entities\Properties\CreatedTime;
use FiveamCode\LaravelNotionApi\Entities\Properties\Date;
use FiveamCode\LaravelNotionApi\Entities\Properties\Email;
use FiveamCode\LaravelNotionApi\Entities\Properties\Files;
use FiveamCode\LaravelNotionApi\Entities\Properties\Formula;
use FiveamCode\LaravelNotionApi\Entities\Properties\LastEditedBy;
use FiveamCode\LaravelNotionApi\Entities\Properties\LastEditedTime;
use FiveamCode\LaravelNotionApi\Entities\Properties\MultiSelect;
use FiveamCode\LaravelNotionApi\Entities\Properties\Number;
use FiveamCode\LaravelNotionApi\Entities\Properties\People;
use FiveamCode\LaravelNotionApi\Entities\Properties\PhoneNumber;
use FiveamCode\LaravelNotionApi\Entities\Properties\Relation;
use FiveamCode\LaravelNotionApi\Entities\Properties\Rollup;
use FiveamCode\LaravelNotionApi\Entities\Properties\Select;
use FiveamCode\LaravelNotionApi\Entities\Properties\Text;
use FiveamCode\LaravelNotionApi\Entities\Properties\Title;
use FiveamCode\LaravelNotionApi\Entities\Properties\Url;
use Illuminate\Support\Facades\Http;

$httpRecorder = null;

beforeEach(function () {
    $this->httpRecorder = Http::recordAndFakeLater('https://api.notion.com/v1/databases*')
        ->storeIn('snapshots/databases');
});

it('should throw a handling exception if no title property is added', function () {
    $this->httpRecorder->nameForNextRequest('400-no-title-property');
    $this->expectException(\FiveamCode\LaravelNotionApi\Exceptions\NotionException::class);
    $this->expectExceptionMessage('Bad Request: (validation_error) (Title is not provided)');
    $this->expectExceptionCode(400);

    Notion::databases()
        ->build()
        ->add(PropertyBuilder::checkbox('Test Checkbox'))
        ->createInPage('0adbc2eb57e84569a700a70d537615be');
});

it('should create a new database with all available properties', function () {
    $this->httpRecorder->nameForNextRequest('all-properties');

    $selectOptions = [
        [
            'name' => 'testing',
            'color' => 'blue',
        ],
    ];

    $multiSelectOptions = [
        [
            'name' => 'testing2',
            'color' => 'yellow',
        ],
    ];

    $scheme = PropertyBuilder::bulk()
        ->title('Test Title')
        ->plain('Test Custom RichText', 'rich_text')
        ->richText('Test RichText')
        ->checkbox('Test Checkbox')
        // ->status() //TODO: Currently not supported due to Notion API versioning
        ->select('Test Select', $selectOptions)
        ->multiSelect('Test MultiSelect', $multiSelectOptions)
        ->number('Test Number', 'dollar')
        ->date('Test Date')
        ->formula('Test Formula', 'prop("Test MultiSelect")')
        ->url('Test Url')
        ->email('Test Email')
        ->phoneNumber('Test PhoneNumber')
        ->people('Test People')
        ->files('Test Files')
        ->relation('Test Relation', '375da18ab01d42d18e95a9dc6a901db1')
        ->rollup('Test Rollup', 'Tag', 'Test Relation', 'unique')
        ->createdBy('Test Created By')
        ->createdTime('Test Created Time')
        ->lastEditedBy('Test Last Edited By')
        ->lastEditedTime('Test Last Edited Time');

    $databaseEntity = Notion::databases()
        ->build()
        // ->inline() //TODO: Currently not supported due to Notion API versioning
        ->title('Created By Testing Database')
        ->coverExternal('https://example.com/cover.jpg')
        ->iconExternal('https://example.com/cover.jpg')
        ->description('This Database has been created by a Pest Test from Laravel')
        ->add($scheme)
        ->createInPage('0adbc2eb57e84569a700a70d537615be');

    expect($databaseEntity->getCover())->toEqual('https://example.com/cover.jpg');
    expect($databaseEntity->getIcon())->toEqual('https://example.com/cover.jpg');
    //TODO: Currently not supported due to Notion API versioning
    // expect($databaseEntity->getDescription())->toEqual('This Database has been created by a Pest Test from Laravel');
    expect($databaseEntity->getTitle())->toEqual('Created By Testing Database');
    expect($databaseEntity->getParentId())->toEqual('0adbc2eb-57e8-4569-a700-a70d537615be');

    expect($databaseEntity->getProperties())->toHaveCount(20);
    expect($databaseEntity->getProperty('Test Title'))->toBeInstanceOf(Title::class);
    expect($databaseEntity->getProperty('Test Custom RichText'))->toBeInstanceOf(Text::class);
    expect($databaseEntity->getProperty('Test RichText'))->toBeInstanceOf(Text::class);
    expect($databaseEntity->getProperty('Test Checkbox'))->toBeInstanceOf(Checkbox::class);
    expect($databaseEntity->getProperty('Test Select'))->toBeInstanceOf(Select::class);
    expect($databaseEntity->getProperty('Test MultiSelect'))->toBeInstanceOf(MultiSelect::class);
    expect($databaseEntity->getProperty('Test Number'))->toBeInstanceOf(Number::class);
    expect($databaseEntity->getProperty('Test Date'))->toBeInstanceOf(Date::class);
    expect($databaseEntity->getProperty('Test Formula'))->toBeInstanceOf(Formula::class);
    expect($databaseEntity->getProperty('Test Url'))->toBeInstanceOf(Url::class);
    expect($databaseEntity->getProperty('Test Email'))->toBeInstanceOf(Email::class);
    expect($databaseEntity->getProperty('Test PhoneNumber'))->toBeInstanceOf(PhoneNumber::class);
    expect($databaseEntity->getProperty('Test People'))->toBeInstanceOf(People::class);
    expect($databaseEntity->getProperty('Test Files'))->toBeInstanceOf(Files::class);
    expect($databaseEntity->getProperty('Test Relation'))->toBeInstanceOf(Relation::class);
    expect($databaseEntity->getProperty('Test Rollup'))->toBeInstanceOf(Rollup::class);
    expect($databaseEntity->getProperty('Test Created By'))->toBeInstanceOf(CreatedBy::class);
    expect($databaseEntity->getProperty('Test Created Time'))->toBeInstanceOf(CreatedTime::class);
    expect($databaseEntity->getProperty('Test Last Edited By'))->toBeInstanceOf(LastEditedBy::class);
    expect($databaseEntity->getProperty('Test Last Edited Time'))->toBeInstanceOf(LastEditedTime::class);

    expect($databaseEntity->getProperty('Test Relation')->getRelation()[0])->toBe('375da18a-b01d-42d1-8e95-a9dc6a901db1');

    expect($databaseEntity->getProperty('Test Rollup')->getContent()["rollup_property_name"])->toBe("Tag");
    expect($databaseEntity->getProperty('Test Rollup')->getContent()["relation_property_name"])->toBe("Test Relation");
    expect($databaseEntity->getProperty('Test Rollup')->getContent()["function"])->toBe("unique");

    expect($databaseEntity->getProperty('Test Select')->getOptions())->toHaveCount(count($selectOptions));
    expect($databaseEntity->getProperty('Test Select')->getOptions()[0]->getName())->toEqual($selectOptions[0]['name']);
    expect($databaseEntity->getProperty('Test Select')->getOptions()[0]->getColor())->toEqual($selectOptions[0]['color']);

    expect($databaseEntity->getProperty('Test MultiSelect')->getOptions())->toHaveCount(count($multiSelectOptions));
    expect($databaseEntity->getProperty('Test MultiSelect')->getOptions()[0]->getName())->toEqual($multiSelectOptions[0]['name']);
    expect($databaseEntity->getProperty('Test MultiSelect')->getOptions()[0]->getColor())->toEqual($multiSelectOptions[0]['color']);

    expect($databaseEntity->getProperty('Test Number')->getRawResponse()['number']['format'])->toBe('dollar');
});

it('should create a new database with default title property', function () {
    $this->httpRecorder->nameForNextRequest('with-emoji-icon');

    $databaseEntity = Notion::databases()
        ->build()
        ->createInPage('0adbc2eb57e84569a700a70d537615be');
    
    expect($databaseEntity->getProperties())->toHaveCount(1);
    expect($databaseEntity->getProperty('Name'))->toBeInstanceOf(Title::class);
});


it('should create a new database with emoji icon', function () {
    $this->httpRecorder->nameForNextRequest('only-title-properties');

    $databaseEntity = Notion::databases()
        ->build()
        ->iconEmoji('👍')
        ->createInPage('0adbc2eb57e84569a700a70d537615be');
    
    expect($databaseEntity->getProperties())->toHaveCount(1);
    expect($databaseEntity->getProperty('Name'))->toBeInstanceOf(Title::class);
    expect($databaseEntity->getIcon())->toBe('👍');
});
