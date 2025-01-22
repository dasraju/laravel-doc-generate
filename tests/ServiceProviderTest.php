<?php

namespace Rajudev\DocGenerator\Tests;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_registers_the_service_provider()
    {
        $this->assertTrue($this->app->providerIsLoaded(\Rajudev\DocGenerator\DocGeneratorServiceProvider::class));
    }
}
