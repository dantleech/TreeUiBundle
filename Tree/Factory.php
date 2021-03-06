<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Factory
{
    protected $container;
    protected $treeServiceMap = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function registerTreeServiceId($name, $serviceId)
    {
        $this->treeServiceMap[$name] = $serviceId;
    }

    public function getTrees()
    {
        $trees = array();
        foreach ($this->treeServiceMap as $name => $id) {
            $trees[] = $this->container->get($id);
        }

        return $trees;
    }

    public function getTreeNames()
    {
        return array_keys($this->treeServiceMap);
    }

    public function getTree($name = null)
    {
        if (null === $name) {
            throw new \InvalidArgumentException(sprintf(
                'No tree name passed to getTree(). Pass one of the registered tree names "%s"',
                implode(',', array_keys($this->treeServiceMap))
            ));
        }

        if (!isset($this->treeServiceMap[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Tree with name "%s" has not been registered',
                $name
            ));
        }

        $tree = $this->container->get($this->treeServiceMap[$name]);

        return $tree;
    }

    public function getStylesheets()
    {
        $ret = array();
        foreach ($this->treeServiceMap as $serviceId) {
            $stylesheets = (array) $this->container->get($serviceId)->getView()->getStylesheets();
            foreach ($stylesheets as $stylesheet) {
                $ret[$stylesheet] = $stylesheet;
            }
        }

        return array_values($ret);
    }

    public function getJavascripts()
    {
        $ret = array();
        foreach ($this->treeServiceMap as $serviceId) {
            $javascripts = (array) $this->container->get($serviceId)->getView()->getJavascripts();
            foreach ($javascripts as $javascript) {
                $ret[$javascript] = $javascript;
            }
        }

        return array_values($ret);
    }

    public function getViewFeatures()
    {
        $features = array();
        $trees = $this->getTrees();
        foreach ($trees as $tree) {
            $features = array_merge($features, array_combine(
                $tree->getView()->getFeatures(),
                $tree->getView()->getFeatures()
            ), $features);
        }

        return array_values($features);
    }
}
