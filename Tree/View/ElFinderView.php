<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewDelegatorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;

class ElFinderView implements ViewInterface
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

    public function processRequest(Request $request)
    {
        $cmd = $request->get('cmd');

        if (!method_exists($this, $cmd)) {
            throw new NotFoundHttpException(sprintf(
                'Command "%s" does not exist',
                $cmd
            ));
        }

        return $this->$cmd($request);
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

    protected function open(Request $request) 
    {
		$target = $request->get('target', 'v1_/');
		$init   = $request->get('init', false);
        $tree   = $request->get('tree', false);

        $target = substr($target, 3);

        $cwdNode = $this->getModel()->getNode($target);
        $cwd = $this->nodeToArray($cwdNode);

		if (!$cwd) {
            return $this->jsonResponse(array(
                'error' => $this->error(
                    self::ERROR_OPEN, 
                    $hash, 
                    self::ERROR_DIR_NOT_FOUND
                )
            ));
        }

		if (!$cwd['read']) {
            return $this->jsonResponse(array(
                'error' => $this->error(
                    self::ERROR_OPEN, 
                    $hash, 
                    self::ERROR_PERM_DENIED
                )
            ));
		}

        $files = array();

		// get folders trees
        if ($tree) {
            $node = new Node;
            $node->setLabel('Default');
            $node->setId('/');
            $node->setHasChildren(true);
            $files = array_merge($files, array($this->nodeToArray($node)));
		}

        $children = $this->getModel()->getChildren($target);

        foreach ($children as $node) {
            $files[] = $this->nodeToArray($node, $cwdNode);
		}
		
		$result = array(
			'cwd'     => $cwd,
			'options' => array(),
			'files'   => $files
		);

		if ($init) {
			$result['api'] = '2.0';
			$result['uplMaxSize'] = ini_get('upload_max_filesize');
		}
		
		return $this->jsonResponse($result);
    }

    protected function jsonResponse($array)
    {
        $json = json_encode($array, true);
        return new Response($json);
    }

    protected function nodeToArray(Node $node, Node $parent = null)
    {
        $arr = array();
        $arr['hash'] = 'v1_'.$node->getId();
        $arr['mime'] = $node->hasChildren() ? 'directory' : 'text';
        $arr['dirs'] = 1;
        $arr['name'] = $node->getLabel();
        $arr['phash'] = $parent ? 'v1_'.$parent->getId() : null;
        $arr['read'] = 1;
        $arr['write'] = 0;
        $arr['size'] = 0;
        $arr['ts'] = 0;
        $arr['volumeid'] = 'v1_';

        return $arr;
    }
}
