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

    public function __construct(Factory $treeFactory)
    {
        $this->treeFactory = $treeFactory;
    }

    protected function getTree(Request $request)
    {
        $name = $request->get('tree_name') ? : null;
        return $this->treeFactory->getTree($name);
    }

    public function delegateAction(Request $request)
    {
        $tree = $this->getTree($request);
        $view = $tree->getView();

        if (!$view instanceof ViewDelegatorInterface) {
            throw new NotFoundHttpException('This model does not support delegating');
        }

        return $view->getDelegatedResponse($request);
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
