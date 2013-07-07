<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function indexAction(Request $request)
    {
        $tf = $this->get('cmf_tree_ui.tree_factory');
        return $this->render('::index.html.twig', array(
            'trees' => $tf->getTrees(),
        ));
    }

    public function treeAction(Request $request)
    {
        if (count($_POST)) {
            var_dump($_POST);
        }
        $name = $request->get('tree_name');
        $tf = $this->get('cmf_tree_ui.tree_factory');
        $tree = $tf->getTree($name);

        return $this->render('::tree/phpcrodm.html.twig', array(
            'tree' => $tree,
            'factory' => $tf,
        ));
    }
}

