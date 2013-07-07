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
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

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
        $loader->load('twig.xml');

        $config = $processor->processConfiguration($configuration, $configs);

        $this->registerTrees($container, $config);
        $this->setupMetadata($container, $config);
        $this->setupConfig('model', $container, $config);
        $this->setupConfig('view', $container, $config);
    }

    protected function registerTrees($container, $config)
    {
        $factory = $container->getDefinition('cmf_tree_ui.tree_factory');

        foreach ($config['tree'] as $name => $treeConfig) {
            $treeDef = new Definition('Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree');
            $treeDef->setScope('prototype');
            $treeDef->addArgument($name);

            $modelServiceId = $this->getMVServiceId($container, 'model', $treeConfig['model']);
            $viewServiceId = $this->getMVServiceId($container, 'view', $treeConfig['view']);

            $treeDef->addArgument(new Reference($modelServiceId));
            $treeDef->addArgument(new Reference($viewServiceId));
            $serviceId = $this->getAlias() . '.tree.'.$name;
            $container->setDefinition($serviceId, $treeDef);
            $factory->addMethodCall('registerTreeServiceId', array($name, $serviceId));
        }
    }

    protected function getMVServiceId($container, $type, $alias) 
    {
        $serviceIds = $container->findTaggedServiceIds(
            $this->getAlias().'.'.$type
        );

        foreach ($serviceIds as $serviceId => $tags) {
            $sAlias = $tags[0]['alias'];
            if ($sAlias = $alias) {
                return $serviceId;
            }
        }

        throw new InvalidArgumentException(sprintf(
            '%s alias "%s" not registered', $type, $alias
        ));
    }

    protected function setupMetadata($container, $config)
    {
        $config['metadata']['mapping_directories']['Doctrine\ODM\PHPCR\Document'] = __DIR__.'/../Resources/config/mapping/DoctrineODMPHPCRDocument';

        $metadataLoader = $container->getDefinition('cmf_tree_ui.metadata.file_locator');
        $metadataLoader->replaceArgument(0, $config['metadata']['mapping_directories']);
    }

    protected function setupConfig($type, $container, $config)
    {
        $serviceIds = $container->findTaggedServiceIds(
            $this->getAlias().'.'.$type
        );

        foreach ($serviceIds as $serviceId => $tags) {
            $sAlias = $tags[0]['alias'];
            $container->setParameter(
                $this->getAlias() . '.config.' . $type . '.' . $sAlias,
                isset($config[$type][$sAlias]) ? $config[$type][$sAlias] : array()
            );
        }
    }
}
