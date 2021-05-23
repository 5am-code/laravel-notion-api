<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Orchestra\Testbench\TestCase;

/**
* Class EndpointPageTest
*
* The fake API responses are based on our test environment (since the current Notion examples do not match with the actual calls).
* @see https://developers.notion.com/reference/get-page
*
* @package FiveamCode\LaravelNotionApi\Tests
*/
class NotionApiTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return ['FiveamCode\LaravelNotionApi\LaravelNotionApiServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Notion' => \FiveamCode\LaravelNotionApi\NotionFacade::class
        ];
    }
}