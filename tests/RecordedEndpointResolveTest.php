<?php

use FiveamCode\LaravelNotionApi\Entities\NotionParent;
use FiveamCode\LaravelNotionApi\Entities\User;
use Illuminate\Support\Facades\Http;

$httpRecorder = null;

beforeEach(function () {
    $this->httpRecorder = Http::recordAndFakeLater([
        'https://api.notion.com/v1/databases*',
        'https://api.notion.com/v1/pages*',
        'https://api.notion.com/v1/blocks*',
        'https://api.notion.com/v1/users*',
    ])->storeIn('snapshots/resolve');
});

it('should resolve the users of specific page properties', function () {
    $this->httpRecorder->nameForNextRequest('for-user-resolve');
    $page = Notion::pages()->find('8890c263e97c45339ef5616d5e75360e');

    $createdBy = $page->getProperty('Created by');
    $lastEditedBy = $page->getProperty('Last edited by');
    $person = $page->getProperty('Person');

    $createdByUser = Notion::resolve()->user($createdBy->getUser());
    $lastEditedByUser = Notion::resolve()->user($lastEditedBy->getUser());
    $personUser = Notion::resolve()->user($person->getPeople()->first());

    expect($createdByUser)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\User::class);
    expect($createdByUser->getName())->toBe('TestUser for NotionForLaravel');
    expect($createdByUser->getId())->toBe('455aad58-7aec-4a39-8c0f-37cab3ca38f5');

    expect($lastEditedByUser)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\User::class);
    expect($lastEditedByUser->getName())->toBe('TestUser for NotionForLaravel');
    expect($lastEditedByUser->getId())->toBe('455aad58-7aec-4a39-8c0f-37cab3ca38f5');

    expect($personUser)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\User::class);
    expect($personUser->getName())->toBe('TestUser for NotionForLaravel');
    expect($personUser->getId())->toBe('455aad58-7aec-4a39-8c0f-37cab3ca38f5');
});

it('should resolve the page parent of a page', function () {
    $page = Notion::pages()->find('a652fac351cc4cc79f5b17eb702793ed');
    $parentPage = Notion::resolve()->parent($page->getParent());

    expect($page->getParent()->isPage())->toBeTrue();

    expect($parentPage)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\Page::class);
    expect($parentPage->getId())->toBe('5ac149b9-d8f1-4d8d-ac05-facefc16ebf7');
    expect($parentPage->getTitle())->toBe('Resolve Endpoint - Testing Suite');
});

it('should return the workspace parent of a page without resolving it', function () {
    $page = Notion::pages()->find('91f70932ee6347b59bc243e09b4cc9b0');
    $parentWorkspace = Notion::resolve()->parent($page->getParent());

    expect($page->getParent()->isWorkspace())->toBeTrue();
    
    expect($parentWorkspace)->toBeInstanceOf(NotionParent::class);
    expect($parentWorkspace->getId())->toBe('1');
    expect($parentWorkspace->getObjectType())->toBe('workspace');
});

it('should resolve the database parent of a page', function () {
    $page = Notion::pages()->find('415d9b6c6e454f42aab2b6e13804cfe9');

    expect($page->getParent()->isDatabase())->toBeTrue();

    $database = Notion::resolve()->parent($page->getParent());
    expect($database)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\Database::class);
    expect($database->getId())->toBe('8a0ef209-8c8a-4fd1-a21c-db7ab327e870');
    expect($database->getTitle())->toBe('Test Table as Parent');
});

it('should resolve the block parent of a block', function () {
    $block = Notion::block('d5f9419b44204c909501b1e2b7569503')->retrieve();

    expect($block->getParent()->isBlock())->toBeTrue();

    $parentBlock = Notion::resolve()->parent($block->getParent());
    expect($parentBlock)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\Blocks\Block::class);
    expect($parentBlock->getId())->toBe('0971ac1a-b6f2-4acc-b706-f5f2ed16ffd6');
    expect($parentBlock->getType())->toBe('paragraph');
});

it('should resolve the page parent of a block', function () {
    $block = Notion::block('0971ac1a-b6f2-4acc-b706-f5f2ed16ffd6')->retrieve();

    $pageParent = Notion::resolve()->parent($block->getParent());
    expect($pageParent)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\Page::class);
    expect($pageParent->getId())->toBe('d946d011-966d-4b14-973f-dc5580f5b024');
    expect($pageParent->getTitle())->toBe('Page for Block Parent Resolve Testing');

    $pageParent = Notion::resolve()->parentOf($block);
    expect($pageParent)->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\Page::class);
    expect($pageParent->getId())->toBe('d946d011-966d-4b14-973f-dc5580f5b024');
    expect($pageParent->getTitle())->toBe('Page for Block Parent Resolve Testing');
});

it('should throw a handling exception when unknown parent type', function () {
    expect(fn () => new NotionParent(['object' => 'unknown', 'id' => '1234']))->toThrow('invalid json-array: the given object is not a valid parent');
});

it('should throw a handling exception when entity without parent', function () {
    $entityWithoutParent = new User(['object' => 'user', 'id' => '1234']);
    expect(fn () => Notion::resolve()->parentOf($entityWithoutParent))->toThrow("The given entity 'user' does not have a parent.");
});

it('should resolve the pages of a database relation', function () {
    $page = Notion::pages()->find('1c56e2ad3d95458c935dae6d57769037');

    $relationPropertyItems = $page->getProperty('Parent Relation Database');
    $relationPages = Notion::resolve()->relations($relationPropertyItems);

    expect($relationPages)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($relationPages->count())->toBe(3);
    expect($relationPages->first())->toBeInstanceOf(\FiveamCode\LaravelNotionApi\Entities\Page::class);
    expect($relationPages->first()->getId())->toBe('cfb10a19-30cc-43a9-8db0-04c43f8cf315');
    expect($relationPages->first()->getTitle())->toBe('test 1');
});

it('should resolve the page titles of a database relation', function () {
    $page = Notion::pages()->find('1c56e2ad3d95458c935dae6d57769037');

    $relationPropertyItems = $page->getProperty('Parent Relation Database');
    $relationPageTitles = Notion::resolve()->relations($relationPropertyItems, true);

    expect($relationPageTitles)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($relationPageTitles->count())->toBe(3);
    expect($relationPageTitles->first())->toBeString();
    expect($relationPageTitles->first())->toBe('test 1');
});
