<?php

namespace Dormilich\Bundle\HttpClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $encoder = $this->getEncoderSection();
        $decoder = $this->getDecoderSection();

        $rootNode = $this->createNode('dormilich_http_client');
        $rootNode
            ->children()
                ->append($encoder)
                ->append($decoder)
            ->end()
        ;

        return $rootNode->end();
    }

    private function createNode(string $name)
    {
        $treeBuilder = new TreeBuilder($name);
        return $treeBuilder->getRootNode();
    }

    private function getEncoderSection()
    {
        $node = $this->createNode('encoder');

        $node->addDefaultsIfNotSet()
            ->children()
                ->integerNode('json')
                    ->info('A combination of the JSON_* encoding constants.')
                    ->min(0)
                    ->defaultValue(0)
                ->end()
                ->enumNode('url')
                    ->info('The URL encoding strategy.')
                    ->values(['php', 'nvp'])
                    ->defaultValue('php')
                ->end()
            ->end()
        ;
        return $node;
    }

    private function getDecoderSection()
    {
        $node = $this->createNode('decoder');

        $node->addDefaultsIfNotSet()
            ->children()
                ->integerNode('json')
                    ->info('A combination of the JSON_* decoding constants.')
                    ->min(0)
                    ->defaultValue(0)
                ->end()
                ->enumNode('url')
                    ->info('The URL encoding strategy.')
                    ->values(['php', 'nvp'])
                    ->defaultValue('php')
                ->end()
            ->end()
        ;
        return $node;
    }
}
