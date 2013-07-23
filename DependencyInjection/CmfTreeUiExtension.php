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
        $loader->load('form.xml');

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
            if ($sAlias == $alias) {
                return $serviceId;
            }
        }

        throw new InvalidArgumentException(sprintf(
            '%s alias "%s" not registered', $type, $alias
        ));
    }

    protected function setupMetadata($container, $config)
    {
        // Register the XML mapping directory for this bundle
        //   (this bundle maps the default DoctrinePHPCRBundle docs)
        // @todo: Automatically register each bundles Resources/config/treeui directory
        $config['metadata']['mapping_directories']['Doctrine\ODM\PHPCR\Document'] = __DIR__.'/../Resources/config/mapping/DoctrineODMPHPCRDocument';

        $fileLoader = $container->getDefinition('cmf_tree_ui.metadata.file_locator');
        $fileLoader->replaceArgument(0, $config['metadata']['mapping_directories']);

        // Register paths of annotated classes. This is required in order
        // to be able to retrieve a list of ALL mapped classes.
        //
        // The annotation driver will work without this mapping, but will
        // not know that classes not mapped here "exist" until they are
        // asked for.
        $bundles = $container->getParameter('kernel.bundles');
        $annotatedDPs = $config['metadata']['annotated_document_paths'];
        $annotatedDirs = array();

        foreach ($annotatedDPs as $bundleName => $path) {
            if (!isset($bundles[$bundleName])) {
                throw new \InvalidArgumentException(sprintf(
                    'Bundle name "%s" is not recognized', $bundleName
                ));
            }

            $bundleFqn = $bundles[$bundleName];
            $refl = new \ReflectionClass($bundleFqn);
            $bundleDir = dirname($refl->getFilename());
            $annotatedDir = $bundleDir . DIRECTORY_SEPARATOR . $path;

            if (!file_exists($annotatedDir)) {
                throw new \InvalidArgumentException(sprintf(
                    'No such directory "%s"', $annotatedDir
                ));
            }

            $annotatedDirs[] = $annotatedDir;
        }

        $annotatedClassNames = $this->getAllAnnotatedClassNames($annotatedDirs);

        $annotationDriver = $container->getDefinition('cmf_tree_ui.metadata.driver.annotation');
        $annotationDriver->replaceArgument(1, $annotatedClassNames);
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

    protected function getAllAnnotatedClassNames($annotatedPaths)
    {
        $start = microtime(true);

        $allClassNames = array();
        $declaredClassPaths = array();
        $declaredStart = 0;
        $declaredCount = 0;

        foreach ($annotatedPaths as $path) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($iterator as $file) {
                $filename = $file->__toString();

                if (in_array($file->getFilename(), array('.', '..'))) {
                    continue;
                }

                // some explanation for this hideous code:
                //
                // Problem: We need to determine the class name of the
                //          file.
                //
                // This is the way Doctrine does it - we get the
                // names of all declared classes and then determine the
                // full path OF EACH FILE. We then check to see if any
                // of those paths correspond to our path, if so: win.

                require_once($filename);

                $declaredClasses = get_declared_classes();

                // optimization: we only populate "new" classes after the 
                // initial run.
                $declaredCount = count($declaredClasses);
                for ($i = $declaredStart; $i < $declaredCount; $i++) {
                    $className = $declaredClasses[$i];
                    $rc = new \ReflectionClass($className);
                    $sourceFile = $rc->getFileName();
                    $declaredClassPaths[$sourceFile] = $className;
                }
                $declaredStart = $declaredCount;

                if (isset($declaredClassPaths[$filename])) {
                    $allClassNames[] = $declaredClassPaths[$filename];
                }
            }
        }
        $end = microtime(true);

        return $allClassNames;
    }
}
