<?php

use Carbon\Carbon;
use FiveamCode\LaravelNotionApi\Entities\Collections\CommentCollection;
use FiveamCode\LaravelNotionApi\Entities\Comment;
use FiveamCode\LaravelNotionApi\Entities\PropertyItems\RichText;
use FiveamCode\LaravelNotionApi\Exceptions\NotionException;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::recordAndFakeLater('https://api.notion.com/v1/comments*')
        ->storeIn('__recorded__/comments');
});

it('should fetch list of comments with an accurate representation of attributes', function () {
    $commentCollection = \Notion::comments()->ofBlock('cb588bcbcbdb4f2eac3db05446b8f5d9');

    $collection = $commentCollection->asCollection();
    $json = $commentCollection->asJson();

    expect($commentCollection)->toBeInstanceOf(CommentCollection::class);
    expect($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($json)->toBeString();

    expect($collection->count())->toBe(1);
    expect($collection->first())->toBeInstanceOf(Comment::class);
    expect($collection->first()->getObjectType())->toBe('comment');
    expect($collection->first()->getId())->toBe('99457ae4-8262-413a-b224-0bd82346d885');
    expect($collection->first()->getCreatedTime())->toEqual(Carbon::parse('2023-02-18T10:53:00.000000+0000')->toDateTime());
    expect($collection->first()->getLastEditedTime())->toEqual(Carbon::parse('2023-02-18T10:53:00.000000+0000')->toDateTime());
    expect($collection->first()->getCreatedBy()->getId())->toBe('04536682-603a-4531-a18f-4fa89fdfb4a8');
    expect($collection->first()->getLastEditedBy())->toBe(null);
    expect($collection->first()->getText())->toBe('This is a Test Comment for Laravel');
    expect($collection->first()->getRichText()->getPlainText())->toBe('This is a Test Comment for Laravel');
    expect($collection->first()->getRichText())->toBeInstanceOf(RichText::class);
    expect($collection->first()->getParentId())->toBe('cb588bcb-cbdb-4f2e-ac3d-b05446b8f5d9');
    expect($collection->first()->getParentType())->toBe('page_id');
    expect($collection->first()->getDiscussionId())->toBe('f203fa27-fe02-40c9-be9f-fb35e2e956ba');

    expect($json)->toBeJson();
});

it('should throw correct exception if block_id has not been found when listing comments', function () {
    $this->expectException(NotionException::class);
    $this->expectExceptionMessage('Not Found');
    $this->expectExceptionCode(404);

    \Notion::comments()->ofBlock('cbf6b0af-6eaa-45ca-9715-9fa147ef6b17')->list();
});
