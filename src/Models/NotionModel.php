<?php

namespace FiveamCode\LaravelNotionApi\Models;

use FiveamCode\LaravelNotionApi\Endpoints\Database;
use FiveamCode\LaravelNotionApi\Entities\Collections\PageCollection;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Support\Collection;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class NotionModel
{
    /**
     * @var ?string
     */
    public static ?string $offset = null;

    /**
     * @var ?Collection<StartCursor>
     */
    public static ?Collection $cursorHistory = null;

    /**
     * @var string
     */
    public static $databaseId = null;

    /**
     * @var string
     */
    public static $preCacheKey = 'laravel-notion-api.notion-model-';

    /**
     * @var int
     */
    public static $cacheDurationInSeconds = 0;

    /**
     * @var boolean
     */
    public static $convertPropsToText = false;

    public $props;

    public Page $page;


    public function __construct($databaseId = null)
    {
        if ($databaseId == null) {
            $databaseId = static::$databaseId;
        }
    }

    public static function createInstance(){
        return new static();
    }


    /**
     * @return string
     */
    public static function databaseId(): string
    {
        if (static::$databaseId !== null) {
            return static::$databaseId;
        }
        throw new \Exception('::getDatabaseId() or ::databaseId must be overridden');
    }

    /**
     * @return 
     */
    public static function notionInstance(): Notion
    {
        return new Notion(config('laravel-notion-api.notion-api-token'));
    }

    /**
     * @return NotionModel
     */
    public static function query(): NotionQueryBuilder
    {
        return new NotionQueryBuilder(static::class);
    }

    /**
     * @return void
     */
    public static function purge(): void
    {
        Cache::forget(static::cacheKey());
    }

    public static function all()
    {
        return self::query()->get();
    }



    /**
     * @return ?static
     */
    public static function first()
    {
        return static::query()
            ->limit(1)
            ->get()
            ->first();
    }

    /**
     * @return static
     */
    public static function find($pageId): Page
    {
        $page = static::notionInstance()
            ->pages()
            ->find($pageId);

        if ($page === null) {
            return null;
        }

        if (str_replace('-', '', $page->getParentId()) !== str_replace('-', '', static::databaseId())) {
            throw new HandlingException('Page is not within the corresponding Database');
        }

        return $page;
    }

    public static function getNextCursor(): ?string
    {
        return self::$nextCursor;
    }

}
