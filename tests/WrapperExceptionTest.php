<?php

namespace FiveamCode\LaravelNotionApi\Tests;

use PHPUnit\Framework\TestCase;
use FiveamCode\LaravelNotionApi\Exceptions\WrapperException;

class WrapperExceptionTest extends TestCase
{


    /** @test */
    public function it_returns_a_wrapper_exception_instance_with_payload()
    {
        $wrapperException = WrapperException::instance('An error occured.', ["foo" => "bar"]);

        $this->assertInstanceOf(
            WrapperException::class,
            $wrapperException
        );

        $this->assertNotEmpty($wrapperException->getPayload());

    }

    /** @test */
    public function it_returns_a_wrapper_exception_instance_without_payload()
    {
        $wrapperException = WrapperException::instance('An error occured.');

        $this->assertInstanceOf(
            WrapperException::class,
            $wrapperException
        );

        $this->assertEmpty($wrapperException->getPayload());
    }
}