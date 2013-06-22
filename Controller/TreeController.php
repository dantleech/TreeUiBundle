<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Controller;

use Symfony\Cmf\Bundle\TreeBrowserBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TreeController
{
    protected $treeModel;
    protected $twig;

    public function __construct(
        TreeFactory $treeFactory,
        TwigEngine $twig
    ) {
        $this->treeFactory = $treeFactory;
        $this->twig = $twig;
    }

    protected function getTree($name = null)
    {
        return $this->treeFactory->createTree($name);
    }

    public function viewAction(Request $request)
    {
        return $this->getTree()->getView()->getViewResponse($request);
    }

    public function getChildrenAction(Request $request)
    {
        return $this->getTree()->getView()->getChildrenResponse($request);
    }
}
