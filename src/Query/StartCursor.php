<?php

namespace FiveamCode\LaravelNotionApi\Query;

class StartCursor {
    private string $cursor;

    public function __construct(string $cursor)
    {
        $this->cursor = $cursor;
    }
}