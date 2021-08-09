<?php

use Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\EncoderCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @covers \Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler\EncoderCompilerPass
 */
class EncoderCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @inheritDoc
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new EncoderCompilerPass());
    }

    /**
     * @test
     */
    public function add_encoder_to_http_client()
    {
        $this->setDefinition('dormilich_http_client.client', new Definition());

        $encoder = new Definition();
        $encoder->addTag('dormilich_http_client.client_encoder');
        $this->setDefinition('encoder', $encoder);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'dormilich_http_client.client',
            'addEncoder',
            [new Reference('encoder')]
        );
    }
}
