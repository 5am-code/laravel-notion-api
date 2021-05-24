<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Notion;
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


    /** @test */
    public function it_returns_notion_instance_with_set_token_and_connection()
    {
        $notion = new Notion("secret_*");
        $notion->v1()->setToken("secret_*");

        $this->assertInstanceOf(Notion::class, $notion);
        $this->assertNotEmpty($notion->getConnection());
    }


    /** @test */
    public function it_throws_a_handling_exception_invalid_version()
    {
        $this->expectException(HandlingException::class);
        $this->expectExceptionMessage("invalid version for notion-api");

        $notion = new Notion("secret_*", "v-does-not-exist");
    }
}