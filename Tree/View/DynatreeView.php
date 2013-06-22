<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Bundle\TwigBundle\TwigEngine;

class DynatreeView implements ViewInterface
{
    protected $tree;
    protected $templating;

    public function __construct(\Twig_Environment $templating)
    {
        $this->templating = $templating;
    }

    public function setTree(Tree $tree)
    {
        $this->tree = $tree;
    }

    protected function getModel()
    {
        return $this->tree->getModel();
    }

    public function getOutput()
    {
        $basePath = $this->tree->getConfig()->getBasePath();
        $rootNode = $this->getModel()->getNode($basePath);
        $uniqId = uniqid();

        $content = $this->templating->render('CmfTreeUiBundle:Dynatree:view.html.twig', array(
            'rootNode' => $rootNode,
            'uniqId' => $uniqId,
        ));

        return $content;
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
