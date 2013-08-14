<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Node;

use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Node;

/**
 * Tree URL Generator Interface
 *
 * Classes implementing this interface must generate URLs
 * requested via. the methods defined in here.
 *
 * All URLs should be AJAX endpoints unless the method name is suffixed with 
 * "Html" - in which case it should be assumed that the URL will be opened 
 * in a new window.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface UrlGeneratorInterface
{
    /**
     * Generate URL for retrieving children nodes of
     * the given node.
     * 
     * @param string $treeName - Name of tree
     * @param Node   $node     - Node for which to retrieve children
     *
     * @return string
     */
    public function children($treeName, Node $node);

    /**
     * Generate URL for moving the given node.
     *
     * NOTE: It is expected that the tree implementation will
     *       add additional parameters such as ?targetNode=foo
     *       for example.
     * 
     * @param string $treeName - Name of tree
     * @param Node   $node     - Node for which to retrieve children
     *
     * @return string
     */
    public function move($treeName, Node $node);

    /**
     * Generate URL for deleting the given node
     * 
     * @param string $treeName - Name of tree
     * @param Node   $node     - Node for which to retrieve children
     *
     * @return string
     */
    public function delete($treeName, Node $node);

    /**
     * Generate URL for renaming the given node
     * 
     * @param string $treeName - Name of tree
     * @param Node   $node     - Node for which to retrieve children
     *
     * @return string
     */
    public function rename($treeName, Node $node);

    /**
     * Generate URL for an HTML page used to create a new node
     * inside or before the given node.
     *
     * NOTE: It is expected that the implementation will add important
     *       information such as the path and the type of node to create.
     * 
     * @param string $treeName   - Name of tree
     * @param Node   $node       - Node for which to retrieve children
     * @param string $childClass - Child class
     *
     * @return string
     */
    public function createHtml($treeName, Node $node, $childClass);

    /**
     * Generate URL for an HTML page used to edit the given node.
     * 
     * @param string $treeName - Name of tree
     * @param Node   $node     - Node for which to retrieve children
     *
     * @return string
     */
    public function editHtml($treeName, Node $node);
}

