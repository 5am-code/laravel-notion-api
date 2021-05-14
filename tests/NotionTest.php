<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Orchestra\Testbench\TestCase;
use FiveamCode\LaravelNotionApi\Notion;

class NotionTest extends TestCase
{

    /** @test */
    public function it_returns_notion_instance_with_set_token_and_connection()
    {
       $notion = new Notion();

       $notion->v1()->setToken("secret_*");

       $this->assertInstanceOf(Notion::class, $notion);
    }

}