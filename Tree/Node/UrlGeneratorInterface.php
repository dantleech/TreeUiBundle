<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;

interface UrlGeneratorInterface
{
    /**
     * Generate a URL for the given operation, treeName and tree node.
     *
     * @param string $operation - one of children, move, delete, edit, create, etc.
     * @param string $treeName  - name of tree
     * @param Node   $node      - Node to generate URL for
     *
     * @return string
     */
    public function fromTreeNode($operation, $treeName, Node $node);
}

