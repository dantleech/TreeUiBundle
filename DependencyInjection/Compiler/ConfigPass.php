<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Create a configuration service for each model and view
 */
class ConfigPass implements CompilerPassInterface
{
    protected $builder;

    public function process(ContainerBuilder $builder)
    {
        $this->builder = $builder;

        $treeModels = $builder->findTaggedServiceIds('cmf_tree_ui.model');
        $this->createConfigs('model', $treeModels);

        $treeViews = $builder->findTaggedServiceIds('cmf_tree_ui.view');
        $this->createConfigs('view', $treeViews);
    }

    protected function createConfigs($type, $serviceIds)
    {
        foreach ($serviceIds as $serviceId => $options) {

            $alias = $options[0]['alias'];
            $configId = sprintf('cmf_tree_ui.config.%s.%s', $type, $alias);

            $configDef = $this->builder->register($configId, sprintf(
                'Symfony\Cmf\Bundle\TreeUiBundle\Tree\%sConfig',
                ucfirst($type)
            ));

            $config = $this->builder->getParameter($configId);
            $configDef->addArgument($config);
            $configDef->addArgument(new Reference($serviceId));

            $serviceDef = $this->builder->getDefinition($serviceId);
            $serviceDef->addMethodCall('configure', array($configDef));
        }
    }
}
