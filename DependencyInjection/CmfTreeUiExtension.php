<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Definition;

class CmfTreeUiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('general.xml');
        $loader->load('models.xml');
        $loader->load('views.xml');
        $loader->load('metadata.xml');

        $config = $processor->processConfiguration($configuration, $configs);

        foreach ($config['tree'] as $name => $treeConfig) {
            if (!$container->hasDefinition($treeConfig['model_service_id'])) {
                throw new \Exception(sprintf(
                    'Tree model_service_id service "%s" not found',
                    $treeConfig['model_service_id']
                ));
            }

            if (!$container->hasDefinition($treeConfig['view_service_id'])) {
                throw new \Exception(sprintf(
                    'Tree view_service_id service "%s" not found',
                    $treeConfig['view_service_id']
                ));
            }

            $treeDef = new Definition('Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree');
            $treeDef->addArgument($name);
            $treeDef->addArgument(new Reference($treeConfig['model_service_id']));
            $treeDef->addArgument(new Reference($treeConfig['view_service_id']));
            $container->setDefinition($this->getAlias() . '.tree.'.$name, $treeDef);
        }
    }
}
