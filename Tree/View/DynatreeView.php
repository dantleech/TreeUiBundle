<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;

class DynatreeView implements ViewInterface
{
    protected $model;

    public function setModel(ModelInterface $model)
    {
        $this->model = $model;
    }

    public function getViewResponse(Request $request)
    {
        $response = new Response;
        $rootNode = $this->model->getNode('/');
        $uniqId = uniqid();

        $content = $this->templating->render('CmfTreeUiBundle:Dynatree:view.html.twig', array(
            'rootNode' => $rootNode,
            'uniqId' => $uniqId,
        ));

        $response->setContent($content);

        return $response;
    }

    public function getChildrenResponse(Request $request)
    {
        $response = new Response;

        $id = $requets->get('key');
        $parentNode = $this->model->getNode($key);
        $children = $this->model->getChildren($parentNode);

        $out = array();

        foreach ($children as $child) {
            $aNode['label'] = $child->getLabel();
            $aNode['key'] = $child->getId();
            $aNode['isLazy'] = true;
            $out[] = $aNode;
        }

        $response->setContent(json_encode($out));

        return $response;
    }

    public function getJavascripts()
    {
        return array();
    }

    public function getStylesheets()
    {
    }
}
