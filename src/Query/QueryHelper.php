<?php

namespace FiveamCode\LaravelNotionApi\Query;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class QueryHelper
{
    /**
     * Contains the property name the query helper works with.
     * @var
     */
    protected ?string $property = null;

    /**
     * Contains all valid timestamps to sort against.
     *
     * @see https://developers.notion.com/reference/post-database-query#post-database-query-sort
     * @var Collection
     */
    protected Collection $validTimestamps;

    /**
     * Contains all valid directions to sort by.
     *
     * @see https://developers.notion.com/reference/post-database-query#post-database-query-sort
     * @var Collection
     */
    protected Collection $validDirections;


    public function __construct()
    {
        $this->validTimestamps = collect(["created_time", "last_edited_time"]);
        $this->validDirections = collect(["ascending", "descending"]);
    }
}