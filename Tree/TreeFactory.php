<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TreeFactory
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createTree($name = null)
    {
        if (null === $name) {
            $name = 'default';
        }

        return $this->container->get('cmf_tree_ui.tree.'.$name);
    }
}
