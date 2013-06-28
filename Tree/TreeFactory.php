<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TreeFactory
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

    public function getTree($name = null)
    {
        if (null === $name) {
            $name = 'default';
        }

        if (!isset($this->treeServiceMap[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Tree with name "%s" has not been registered'
            ));
        }

        return $this->container->get($this->treeServiceMap[$name]);
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
}
