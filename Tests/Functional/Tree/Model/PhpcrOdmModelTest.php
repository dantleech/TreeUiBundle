<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class PhpcrOdmModelTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));
        $this->model = $this->getContainer()->get('cmf_tree_ui.model.phpcr_odm');
    }

    public function testGetChildren()
    {
        $nodes = $this->model->getChildren('/');
        $this->assertCount(1, $nodes);

        $nodes = $this->model->getChildren('/test');
        $this->assertCount(1, $nodes);

        $nodes = $this->model->getChildren('/test/menu');
        $this->assertCount(4, $nodes);
    }

    public function testGetAncestors()
    {
        $ancestors = $this->model->getAncestors('/test/menu/item2/subitem1');

        foreach (array(
            '/', 
            '/test', 
            '/test/menu', 
            '/test/menu/item2') as $name) 
        {
            $ancestor = array_shift($ancestors);
            $this->assertEquals($name, $ancestor->getId());
        }
    }
}
