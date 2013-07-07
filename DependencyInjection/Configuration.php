<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    /**
     * Returns the config tree builder.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        /** @var $builder \Symfony\Component\Config\Definition\Builder\NodeBuilder */
        $builder = $treeBuilder->root('cmf_tree_ui');

        $builder->children()
            ->arrayNode('model')
                ->useAttributeAsKey('alias')
                ->prototype('array')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
            ->arrayNode('view')
                ->useAttributeAsKey('alias')
                ->prototype('array')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
            ->arrayNode('tree')
                ->isRequired()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('model')->isRequired()->end()
                        ->scalarNode('view')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('metadata')
                ->addDefaultsIfNotSet(true)
                ->children()
                ->arrayNode('mapping_directories')
                        ->useAttributeAsKey('prefix')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

