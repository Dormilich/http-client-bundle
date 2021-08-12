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

        $this->configureJsonTransformers($mergedConfig, $container);
        $this->configureUrlTransformer('encoder', $mergedConfig, $container);
        $this->configureUrlTransformer('decoder', $mergedConfig, $container);
    }

    private function loadDefinitions(ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');
    }

    private function configureJsonTransformers(array $config, ContainerBuilder $container): void
    {
        $encoder = $container->getDefinition('dormilich_http_client.json_encoder');
        $encoder->setArgument(0, $config['encoder']['json']);

        $decoder = $container->getDefinition('dormilich_http_client.json_decoder');
        $decoder->setArgument(0, $config['decoder']['json']);
    }

    private function configureUrlTransformer(string $type, array $config, ContainerBuilder $container): void
    {
        if ($value = $config[$type]['url']) {
            $alias = "dormilich_http_client.{$value}_query";
            $service = "dormilich_http_client.query_{$type}";
            if ($container->has($alias) and $container->has($service)) {
                $container->setAlias($service, $alias);
            }
        }
    }
}
