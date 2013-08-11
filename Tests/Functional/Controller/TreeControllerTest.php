<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Controller;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

class TreeControllerTest extends BaseTestCase
{
    public function testController()
    {
        $controller = $this->getContainer()->get('cmf_tree_ui.controller.tree');
        $req = new Request(array(), array(
            'cmf_tree_ui_tree_name' => 'default'
        ));
        $res = $controller->viewAction($req);
    }
}
