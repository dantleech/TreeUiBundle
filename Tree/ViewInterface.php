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
    const FEATURE_BROWSE = 'can_browse';
    const FEATURE_FORM_ELEMENT_SINGLE = 'can_form_element';
    const FEATURE_FORM_ELEMENT_MULTIPLE = 'can_form_element';

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

