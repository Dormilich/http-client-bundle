<?php

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\DormilichHttpClientExtension;
use Dormilich\HttpClient\Client;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @covers \Dormilich\Bundle\HttpClientBundle\DependencyInjection\DormilichHttpClientExtension
 */
class HttpClientExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @inheritDoc
     */
    protected function getContainerExtensions(): array
    {
        return [new DormilichHttpClientExtension()];
    }

    /**
     * @test
     */
    public function load_http_client_service()
    {
        $this->load();
        $this->assertContainerBuilderHasService('dormilich_http_client.client', Client::class);
    }
}
