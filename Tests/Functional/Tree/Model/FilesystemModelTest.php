<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class FilesystemModelTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->model = $this->getContainer()->get('cmf_tree_ui.model.filesystem');
        $this->model->setRoot(__DIR__.'/filesystem');
    }

    public function testGetNode()
    {
        $node = $this->model->getNode('/folder1');
    }

    public function testGetChildren()
    {
        $nodes = $this->model->getChildren('/');
        $this->assertCount(3, $nodes);

        $nodes = $this->model->getChildren('/folder1');
        $this->assertCount(0, $nodes);

        $nodes = $this->model->getChildren('/folder2');
        $this->assertCount(2, $nodes);
    }

    public function testGetAncestors()
    {
        $ancestors = $this->model->getAncestors('/folder2/file3');

        foreach (array(
            '/', 
            '/folder2', 
        ) as $name) 
        {
            $ancestor = array_shift($ancestors);
            $this->assertEquals($name, $ancestor->getId());
        }
    }
}
