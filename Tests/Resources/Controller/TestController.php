<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\Menu;

class TestController extends Controller
{
    protected function getDm()
    {
        return $this->get('doctrine_phpcr.odm.document_manager');
    }

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

    public function formTestAction(Request $request)
    {
        $menuItem = $this->getDm()->find(null, '/test/menu/item1');;

        $builder = $this->createFormBuilder($menuItem);
        $builder->add('title', 'text');
        $builder->add('menu', 'cmf_tree_ui_tree', array(
            'tree_name' => 'fancytree_phpcrodm',
            'options' => array(
                'context_menu_enable' => true,
                'drag_and_drop' => true,

                'root_path' => '/test',
            ),
        ));
        $form = $builder->getForm();

        return $this->render('::form/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
