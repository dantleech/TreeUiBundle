<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\TreeViewOptionsResolver;

/**
 * STATUS: Cannot seem to get nodes to be auto-expanded and selected.
 *         Going to try implementing Dynatree also.
 */
class FancyTreeView implements ViewInterface
{
    protected $tree;
    protected $templating;
    protected $urlGenerator;
    protected $optionsResolver;

    public function __construct(
        \Twig_Environment $templating, 
        UrlGeneratorInterface $urlGenerator,
        TreeViewOptionsResolver $resolver
    )
    {
        $this->templating = $templating;
        $this->urlGenerator = $urlGenerator;
        $this->optionsResolver = $resolver;
    }

    public function getFeatures()
    {
        return array(
            ViewInterface::FEATURE_BROWSE,
            ViewInterface::FEATURE_PRE_SELECT_NODE,
            ViewInterface::FEATURE_FORM_INPUT,
            ViewInterface::FEATURE_FORM_INPUT_MULTIPLE,
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
        $options = $this->optionsResolver->resolve($options);

        $basePath = $this->tree->getConfig()->getBasePath();
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

        $content = $this->templating->render('CmfTreeUiBundle:FancyTree:view.html.twig', array(
            'rootNode' => $rootNode,
            'tree' => $this->tree,
            'uniqId' => $uniqId,
            'options' => $options,
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
