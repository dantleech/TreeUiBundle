<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node\UrlGenerator;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as BaseUrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\TreeMetadata;

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

    /**
     * {@inheritDoc}
     */
    public function children($treeName, Node $node)
    {
        return $this->getRoute('children', $treeName, $node);
    }

    /**
     * {@inheritDoc}
     */
    public function move($treeName, Node $node)
    {
        return $this->getRoute('move', $treeName, $node);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($treeName, Node $node)
    {
        return $this->getRoute('delete', $treeName, $node);
    }

    /**
     * {@inheritDoc}
     */
    public function rename($treeName, Node $node)
    {
        return $this->getRoute('rename', $treeName, $node);
    }

    /**
     * {@inheritDoc}
     */
    public function createHtml($treeName, Node $node, $childClass)
    {
        return $this->getRoute('create_html', $treeName, $node, array(
            'child_class_name' => $childClass,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function editHtml($treeName, Node $node)
    {
        return $this->getRoute('edit_html', $treeName, $node);
    }

    protected function getRoute($operation, $treeName, Node $node, $options = array())
    {
        $prefix = '_cmf_tree_ui_';
        $routeName = $prefix.$operation;

        return $this->baseUrlGenerator->generate($routeName, array_merge(array(
            'cmf_tree_ui_tree_name' => $treeName,
            'cmf_tree_ui_node_id' => $node->getId(),
        ),  $options));
    }
}
