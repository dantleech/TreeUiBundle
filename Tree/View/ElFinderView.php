<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ElFinderView implements ViewInterface
{
    protected $tree;
    protected $templating;
    protected $urlGenerator;
    protected $config;

    public function __construct(
        \Twig_Environment $templating, 
        UrlGeneratorInterface $urlGenerator,
        ViewConfig $config
    )
    {
        $this->templating = $templating;
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
    }

    public function getFeatures()
    {
        return array(
            ViewInterface::FEATURE_BROWSE,
        );
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
        $rootNode = $this->getModel()->getNode($basePath);
        $uniqId = uniqid();

        $content = $this->templating->render('CmfTreeUiBundle:FancyTree:view.html.twig', array(
            'rootNode' => $rootNode,
            'tree' => $this->tree,
            'uniqId' => $uniqId,
            'keyPath' => $keyPath,
        ));

        return $content;
    }

    public function getChildrenResponse(Request $request)
    {
        $response = new Response;

        $id = $request->get('node_id', '/');
        $children = $this->getModel()->getChildren($id);

        $out = array();
        $treeName = $this->tree->getName();

        foreach ($children as $child) {
            $aNode['title'] = $child->getLabel();
            $aNode['key'] = $child->getId();
            $aNode['lazy'] = $child->hasChildren();
            $aNode['folder'] = $child->hasChildren();
            $aNode['children_url'] = $this->urlGenerator->generate('_cmf_tree_ui_children', array(
                'tree_name' => $treeName,
                'node_id' => $child->getId(),
            ));
            $out[] = $aNode;
        }

        $response->setContent(json_encode($out));

        return $response;
    }

    public function getJavascripts()
    {
        return array(
            'bundles/cmftreeui/components/fancytree/src/jquery.fancytree.js',
        );
    }

    public function getStylesheets()
    {
        return array(
            'bundles/cmftreeui/components/fancytree/src/skin-xp/ui.fancytree.css',
        );
    }
}

