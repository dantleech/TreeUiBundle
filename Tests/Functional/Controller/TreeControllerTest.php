<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Controller;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

class TreeControllerTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));
    }

    public function testController()
    {
        $controller = $this->getContainer()->get('cmf_tree_ui.controller.tree');
        $req = new Request(array(), array('_base_path' => '/test/menu'));
        $res = $controller->viewAction($req);
    }
}
