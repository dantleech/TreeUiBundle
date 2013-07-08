<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewConfig;

/**
 * This interface will be implemented by classes which
 * convert the output of the Model class to something
 * acceptable by a given frontend interface.
 *
 * Examples include jsTree and dynatree/fancytree
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface ViewInterface extends FeaturableInterface
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
     */
    public function setTree(Tree $tree);

    /**
     * Return the response for the view request.
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getOutput($output = array());

    /**
     * Process a backend command
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processRequest(Request $request);

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

    /**
     * Configure the class
     *
     * @param array $options
     *
     * @return void
     */
    public function configure(ViewConfig $config);
}
