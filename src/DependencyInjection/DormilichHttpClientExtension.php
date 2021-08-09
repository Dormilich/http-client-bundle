<?php

namespace Dormilich\Bundle\HttpClientBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class DormilichHttpClientExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $this->loadDefinitions($container);

        $this->configureJsonTransformer($mergedConfig['transformer'], $container);
        $this->configureUrlTransformer($mergedConfig['transformer'], $container);
    }

    private function loadDefinitions(ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');
    }

    private function configureJsonTransformer(array $config, ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('dormilich_http_client.json_transformer');
        $definition->setArgument(0, $config['json']);
    }

    private function configureUrlTransformer(array $config, ContainerBuilder $container): void
    {
        if ($type = $config['url']) {
            $alias = "dormilich_http_client.{$type}_query";
            if ($container->has($alias)) {
                $container->setAlias('dormilich_http_client.query_parser', $alias);
            }
        }
    }
}
