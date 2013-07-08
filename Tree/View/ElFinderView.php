<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewDelegatorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;

class ElFinderView implements ViewDelegatorInterface
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
        $rootNode = $this->getModel()->getNode($options['root_path']);
        $uniqId = uniqid();

        $content = $this->templating->render('CmfTreeUiBundle:ElFinder:view.html.twig', array(
            'rootNode' => $rootNode,
            'tree' => $this->tree,
            'uniqId' => $uniqId,
        ));

        return $content;
    }

    public function getDelegatedResponse(Request $request)
    {
        // hmm should split the interfaces up into individual 
        return new Response('');
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
            'bundles/cmftreeui/components/elFinder/js/elfinder.min.js',
        );
    }

    public function getStylesheets()
    {
        return array(
            'bundles/cmftreeui/components/elFinder/css/elfinder.min.css',
            'bundles/cmftreeui/components/elFinder/css/theme.css',
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
