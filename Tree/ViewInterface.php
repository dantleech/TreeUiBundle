<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;

/**
 * This interface will be implemented by classes which
 * convert the output of the Model class to something
 * acceptable by a given frontend interface.
 *
 * Examples include jsTree and dynatree/fancytree
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface ViewInterface
{
    /**
     * Ability to browse the tree.
     */
    const FEATURE_BROWSE = 'browse';

    /**
     * Ability to pre-select a node.
     */
    const FEATURE_PRE_SELECT_NODE = 'pre_select_node';

    /**
     * Will act as a form input.
     */
    const FEATURE_FORM_INPUT = 'form_input';

    /**
     * Will act as a form input multiple
     */
    const FEATURE_FORM_INPUT_MULTIPLE = 'form_input_multiple';

    /**
     * Return the set of supported features
     */
    public function getFeatures();

    /**
     * Set the model from which the view should get
     * its data.
     *
     * @param ModelInterface $model
     */
    public function setTree(Tree $tree);

    /**
     * Return the response for the view request.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getOutput($options = array());

    /**
     * Return the response for a children request.
     *
     * @param Symfony\Component\HttpFoundation\Request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getChildrenResponse(Request $request);

    /**
     * Return an array of javascript assets required by
     * the plugin
     *
     * @return array
     */
    public function getJavascripts();

    /**
     * Return an array of stylesheets required by the
     * plugin.
     *
     * @return array
     */
    public function getStylesheets();
}

