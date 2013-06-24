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
        $treeBuilder->root('cmf_tree_ui')
            ->children()
            ->arrayNode('tree')
                ->isRequired()
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('model_service_id')->isRequired()->end()
                        ->scalarNode('view_service_id')->isRequired()->end()
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

