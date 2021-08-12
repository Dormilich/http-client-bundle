<?php

namespace Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TransformerCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('dormilich_http_client.client')) {
            return;
        }

        $client = $container->findDefinition('dormilich_http_client.client');
        $taggedServices = $container->findTaggedServiceIds('dormilich_http_client.client_transformer');

        foreach ($taggedServices as $id => $attr) {
            $client->addMethodCall('addTransformer', [new Reference($id)]);
        }
    }
}
