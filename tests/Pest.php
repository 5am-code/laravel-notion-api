<?php

use Dotenv\Dotenv;
use FiveamCode\LaravelNotionApi\Tests\NotionApiTest;
use Illuminate\Support\Facades\Config;

uses(NotionApiTest::class)->beforeEach(function () {
    if (file_exists(__DIR__.'/../.env.testing')) {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..', '.env.testing');
        $dotenv->load();
    }
    Config::set('laravel-notion-api.notion-api-token', env('NOTION_API_TOKEN', ''));
})->in(__DIR__);
