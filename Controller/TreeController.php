<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Controller;

use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\TreeFactory;

class TreeController
{
    protected $treeModel;

    public function __construct(TreeFactory $treeFactory)
    {
        $this->treeFactory = $treeFactory;
    }

    protected function getTree(Request $request)
    {
        $name = $request->get('_tree_name') ? : null;
        return $this->treeFactory->createTree($name);
    }

    public function viewAction(Request $request)
    {
        $basePath = $request->get('_base_path');

        $tree = $this->getTree($request);
        $tree->getConfig()->setBasePath($basePath);

        $content = $tree->getView()->getOutput();

        return new Response($content);
    }

    public function getChildrenAction(Request $request)
    {
        return $this->getTree()->getView()->getChildrenResponse($request);
    }
}
