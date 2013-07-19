<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewConfig;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use PHPCR\Util\PathHelper;

class FancyTreeView extends AbstractStandardView
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

    public function configure(ViewConfig $config)
    {
        $config->setDefaults(array(
            'root_path' => '/',
            'drag_and_drop' => false
        ));

        $config->setRequired(array(
            'root_path'
        ));

        $this->config = $config;
    }

    public function getFeatures()
    {
        return array(
            ViewInterface::FEATURE_CONTEXT_MENU,
            //ViewInterface::FEATURE_BROWSE,
            //ViewInterface::FEATURE_PRE_SELECT_NODE,
            //ViewInterface::FEATURE_FORM_INPUT,
            //ViewInterface::FEATURE_FORM_INPUT_MULTIPLE,
            //ViewInterface::FEATURE_DRAG_AND_DROP,
        );
    }

    public function setTree(Tree $tree)
    {
        $this->tree = $tree;
    }

    /**
     * @return Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface
     */
    protected function getModel()
    {
        return $this->tree->getModel();
    }

    protected function jsonResponse($array)
    {
        return new Response(json_encode($array, true));
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

        $content = $this->templating->render('CmfTreeUiBundle:FancyTree:view.html.twig', array(
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
        $id = $request->get('cmf_tree_ui_node_id', '/');
        $children = $this->getModel()->getChildren($id);

        $out = array();
        $treeName = $this->tree->getName();

        foreach ($children as $child) {
            $aNode['title'] = $child->getLabel();
            $aNode['tooltip'] = $child->getClassLabel();
            $aNode['key'] = $child->getId();
            $aNode['lazy'] = $child->hasChildren();
            $aNode['folder'] = $child->hasChildren();

            $aNode['children_url'] = $this->urlGenerator->fromTreeNode('children', $treeName, $child);

            $aNode['move_url'] = $this->urlGenerator->fromTreeNode('move', $treeName, $child);

            $aNode['delete_url'] = $this->urlGenerator->fromTreeNode('delete', $treeName, $child);

            $aNode['rename_url'] = $this->urlGenerator->fromTreeNode('rename', $treeName, $child);
            $aNode['childClasses'] = array();

            foreach ($child->getChildClasses() as $childClassFqn => $childClassMeta) {
                $aNode['childClasses'][$childClassFqn] = $childClassMeta->classLabel;
            }

            $out[] = $aNode;
        }

        return $this->jsonResponse($out);
    }

    public function renameResponse(Request $request)
    {
        $res = array(
            'ok' => 1,
        );

        $nodeId = $request->get('cmf_tree_ui_node_id');
        $newName = $request->get('cmf_tree_ui_new_name');

        try {
            $this->getModel()->rename($nodeId, $newName);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $res['ok'] = 0;
            $res['message'] = $message;
        }

        return $this->jsonResponse($res);
    }

    public function moveResponse(Request $request)
    {
        $res = array(
            'ok' => 1,
        );

        $nodeId = $request->get('cmf_tree_ui_node_id');
        $targetNodeId = $request->get('cmf_tree_ui_target_node_id');
        $mode = $request->get('cmf_tree_ui_target_mode');
        $before = $mode == 'before';

        $targetParentPath = PathHelper::getParentPath($targetNodeId);
        $targetNodeName = PathHelper::getNodeName($targetNodeId);
        $nodeName = PathHelper::getNodeName($nodeId);
        $reorder = true;

        if ($mode == 'over') {
            $targetPath = $targetNodeId.'/'.$nodeName;
            $reorder = false;
        } else {
            $targetPath = $targetParentPath.'/'.$nodeName;
        }

        try {
            $this->getModel()->move($nodeId, $targetPath);
            if ($reorder) {
                $this->getModel()->reorder($targetParentPath, $nodeName, $targetNodeName, $before);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $res['ok'] = 0;
            $res['message'] = $message;
        }

        return $this->jsonResponse($res);
    }

    public function deleteResponse(Request $request)
    {
        $res = array(
            'ok' => 1
        );

        $nodeId = $request->get('cmf_tree_ui_node_id');

        try {
            $this->getModel()->delete($nodeId);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $res['ok'] = 0;
            $res['message'] = $message;
        }

        return $this->jsonResponse($res);
    }

    public function getJavascripts()
    {
        return array(
            'bundles/cmftreeui/components/fancytree/src/jquery.fancytree.js',
            'bundles/cmftreeui/components/fancytree/src/jquery.fancytree.dnd.js',
            'bundles/cmftreeui/components/fancytree/src/jquery.fancytree.menu.js',
        );
    }

    public function getStylesheets()
    {
        return array(
            'bundles/cmftreeui/components/fancytree/src/skin-xp/ui.fancytree.css',
            'bundles/cmftreeui/components/jquery-ui/themes/smoothness/jquery-ui.min.css',
            'bundles/cmftreeui/components/jquery-ui/themes/smoothness/jquery.ui.theme.css',
        );
    }
}
