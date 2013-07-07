<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Controller;

use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Factory;

class TreeController
{
    protected $treeModel;

    public function __construct(Factory $treeFactory)
    {
        $this->treeFactory = $treeFactory;
    }

    protected function getTree(Request $request)
    {
        $name = $request->get('tree_name') ? : null;
        return $this->treeFactory->getTree($name);
    }

    public function viewAction(Request $request)
    {
        $tree = $this->getTree($request);
        $content = $tree->getView()->getOutput();

        return new Response($content);
    }

    public function childrenAction(Request $request)
    {
        return $this->getTree($request)->getView()->getChildrenResponse($request);
    }
}
