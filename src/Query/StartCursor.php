<?php

namespace FiveamCode\LaravelNotionApi\Query;

use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;
use Illuminate\Support\Collection;

class StartCursor {
    private string $cursor;

    public function __construct(string $cursor)
    {
        $this->cursor = $cursor;
    }
}