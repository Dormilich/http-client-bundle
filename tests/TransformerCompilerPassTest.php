<?php

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @covers \Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\TransformerCompilerPass
 */
class TransformerCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @inheritDoc
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TransformerCompilerPass());
    }

    /**
     * @test
     */
    public function add_transformer_to_http_client()
    {
        $this->setDefinition('dormilich_http_client.client', new Definition());

        $transformer = new Definition();
        $transformer->addTag('dormilich_http_client.client_transformer');
        $this->setDefinition('transformer', $transformer);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'dormilich_http_client.client',
            'addTransformer',
            [new Reference('transformer')]
        );
    }
}
