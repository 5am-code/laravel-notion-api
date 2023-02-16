<?php

namespace FiveamCode\LaravelNotionApi\Models;

use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
use FiveamCode\LaravelNotionApi\Query\StartCursor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

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
     * @var bool
     */
    public static $convertPropsToText = false;

    public Page $page;

    public function __construct($databaseId = null)
    {
        if ($databaseId == null) {
            $databaseId = static::$databaseId;
        }
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public static function createInstance()
    {
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
     * @return NotionQueryBuilder
     */
    public static function query(): NotionQueryBuilder
    {
        return new NotionQueryBuilder(static::class);
    }

    /**
     * @return NotionQueryBuilder
     */
    public static function where($property, $operator, $value = null)
    {
        return static::query()->where($property, $operator, $value);
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
