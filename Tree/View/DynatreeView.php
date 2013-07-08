<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\TreeViewOptionsResolver;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Config;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewConfig;

class DynatreeView extends AbstractStandardView
{
    protected $tree;
    protected $templating;
    protected $urlGenerator;
    protected $config;

    public function __construct(
        \Twig_Environment $templating, 
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->templating = $templating;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFeatures()
    {
        return array(
            ViewInterface::FEATURE_BROWSE,
            ViewInterface::FEATURE_PRE_SELECT_NODE,
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

    public function getOutput($options = array())
    {
        $options = $this->config->getOptions($options);

        $basePath = $options['root_path'];
        $rootNode = $this->getModel()->getNode($basePath);
        $uniqId = uniqid();

        // sort out key path
        $keyPath = '';
        if ($options['select_node'] != '/') {
            $ancestors = $this->getModel()->getAncestors($options['select_node']);
            // we don't like the root element ...
            array_shift($ancestors);
            foreach ($ancestors as $ancestor) {
                $keyPath .= '&'.$ancestor->getId();
            }
        }

        $content = $this->templating->render('CmfTreeUiBundle:Dynatree:view.html.twig', array(
            'rootNode' => $rootNode,
            'tree' => $this->tree,
            'uniqId' => $uniqId,
            'options' => $options,
            'keyPath' => $keyPath,
        ));

        return $content;
    }

    public function childrenResponse(Request $request)
    {
        $response = new Response;

        $id = $request->get('cmf_tree_ui_node_id', '/');
        $children = $this->getModel()->getChildren($id);

        $out = array();
        $treeName = $this->tree->getName();

        foreach ($children as $child) {
            $aNode['title'] = $child->getLabel();
            $aNode['key'] = $child->getId();
            $aNode['isLazy'] = $child->hasChildren();
            $aNode['isFolder'] = $child->hasChildren();
            $aNode['children_url'] = $this->urlGenerator->generate('_cmf_tree_ui_children', array(
                'cmf_tree_ui_tree_name' => $treeName,
                'cmf_tree_ui_node_id' => $child->getId(),
            ));
            $out[] = $aNode;
        }

        $response->setContent(json_encode($out));

        return $response;
    }

    public function getJavascripts()
    {
        return array(
            'bundles/cmftreeui/components/dynatree/src/jquery.dynatree.js',
        );
    }

    public function getStylesheets()
    {
        return array(
            'bundles/cmftreeui/components/dynatree/src/skin/ui.dynatree.css',
        );
    }

    public function configure(ViewConfig $config)
    {
        $config->setDefaults(array(
            'root_path' => '/',
        ));

        $config->setRequired(array(
            'root_path'
        ));

        $this->config = $config;
    }
}
