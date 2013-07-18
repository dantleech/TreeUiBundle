<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node\UrlGenerator;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as BaseUrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;

/**
 * URLs generated with this node url generator are directed
 * back to the native TreeUiBundle controller
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class DefaultUrlGenerator implements UrlGeneratorInterface
{
    protected $baseUrlGenerator;

    public function __construct(BaseUrlGeneratorInterface $baseUrlGenerator)
    {
        $this->baseUrlGenerator = $baseUrlGenerator;
    }

    public function fromTreeNode($operation, $treeName, Node $node)
    {
        $prefix = '_cmf_tree_ui_';
        $routeName = $prefix.$operation;

        return $this->baseUrlGenerator->generate($routeName, array(
            'cmf_tree_ui_tree_name' => $treeName,
            'cmf_tree_ui_node_id' => $node->getId(),
        ));
    }
}
