<?php

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\DecoderCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @covers \Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\DecoderCompilerPass
 */
class DecoderCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @inheritDoc
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DecoderCompilerPass());
    }

    /**
     * @test
     */
    public function add_decoder_to_http_client()
    {
        $this->setDefinition('dormilich_http_client.client', new Definition());

        $decoder = new Definition();
        $decoder->addTag('dormilich_http_client.client_decoder');
        $this->setDefinition('decoder', $decoder);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'dormilich_http_client.client',
            'addDecoder',
            [new Reference('decoder')]
        );
    }
}
