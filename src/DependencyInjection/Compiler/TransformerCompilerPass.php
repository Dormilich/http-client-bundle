<?php

namespace Dormilich\Bundle\HttpClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

use function array_intersect;
use function array_keys;
use function count;
use function reset;
use function str_replace;

class TransformerCompilerPass implements CompilerPassInterface
{
    private array $tags = [
        'dormilich_http_client.decode_success',
        'dormilich_http_client.decode_error',
        'dormilich_http_client.decode_client_error',
        'dormilich_http_client.decode_server_error',
    ];

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
            $arguments = [new Reference($id)];
            $service = $container->findDefinition($id);
            if ($matcher = $this->getStatusMatcher($service)) {
                if ($container->has($matcher)) {
                    $arguments[] = new Reference($matcher);
                }
            }
            $client->addMethodCall('addTransformer', $arguments);
        }
    }

    private function getStatusMatcher(Definition $definition): ?string
    {
        $tags = $definition->getTags();
        $status = array_intersect(array_keys($tags), $this->tags);

        if (count($status) === 0) {
            return null;
        }
        return str_replace('.decode_', '.status_', reset($status));
    }
}
