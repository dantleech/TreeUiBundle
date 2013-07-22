<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Controller;

use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Factory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewDelegatorInterface;

class TreeController
{
    protected $treeModel;
    protected $twig;

    public function __construct(Factory $treeFactory, \Twig_Environment $twig)
    {
        $this->treeFactory = $treeFactory;
        $this->twig = $twig;
    }

    protected function getTree(Request $request)
    {
        $name = $request->get('cmf_tree_ui_tree_name') ? : null;
        return $this->treeFactory->getTree($name);
    }

    /**
     * Process a command, generally this is the backend
     * interface to the tree.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processAction(Request $request)
    {
        $tree = $this->getTree($request);
        $view = $tree->getView();

        return $view->processRequest($request);
    }

    /**
     * Render a tree - note that this is perhaps more commonly
     * achieved through the twig helper and not through this
     * controller.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function viewAction(Request $request)
    {
        $tree = $this->getTree($request);
        $content = $tree->getView()->getOutput();

        return new Response($content);
    }

    /**
     * Process an EDIT request for a given node in HTML.
     * This is currently a stubb feature - actual edition
     * should be done by something else, i.e. Sonata.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editHtmlAction(Request $request)
    {
        $tree = $this->getTree($request);
        $node = $tree->getModel()->getNode($request->get('cmf_tree_ui_node_id'));
        $childClassName = $request->get('child_class_name', null);
        $mode = $request->get('mode', null);
        $res = $this->twig->render('CmfTreeUiBundle:Crud:defaultHtml.html.twig', array(
            'node' => $node,
            'tree' => $tree,
            'childClassName' => $childClassName,
            'mode' => $mode,
        ));

        return new Response($res);
    }

    /**
     * Process an CREATE request for a given node in HTML.
     * This is currently a stubb feature - actual edition
     * should be done by something else, i.e. Sonata.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createHtmlAction(Request $request)
    {
        return $this->editHtmlAction($request);
    }
}
