<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use FiveamCode\LaravelNotionApi\LaravelNotionApiServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelNotionApiServiceProvider::class,
        ];
    }
}