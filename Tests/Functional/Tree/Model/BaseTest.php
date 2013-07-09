<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;

abstract class BaseTest extends BaseTestCase
{
    public function setUp()
    {
        $this->model = $this->getModel();
    }

    abstract protected function getModel();

    protected function requiresFeature($featureName)
    {
        $features = $this->model->getFeatures();
        if (!in_array($featureName, $features)) {
            $this->markTestSkipped(sprintf(
                'Model does not support "%s" feature',
                $featureName
            ));
        }
    }

    public function testGetNode()
    {
        $this->requiresFeature(ModelInterface::FEATURE_GET_NODE);

        $node = $this->model->getNode('/test/menu');
    }

    public function testGetChildren()
    {
        $this->requiresFeature(ModelInterface::FEATURE_GET_CHILDREN);

        $nodes = $this->model->getChildren('/');
        $this->assertCount(1, $nodes);

        $nodes = $this->model->getChildren('/test');
        $this->assertCount(1, $nodes);

        $nodes = $this->model->getChildren('/test/menu');
        $this->assertCount(4, $nodes);
    }

    public function testMove()
    {
        $this->requiresFeature(ModelInterface::FEATURE_MOVE);

        $this->model->move(
            '/test/menu/item1', 
            '/test/menu/item2/subitemX',
            false
        );

        $children = $this->model->getChildren('/test/menu/item2');
        $this->assertCount(3, $children);
    }

    public function provideReorder()
    {
        return array(
            array(false),
            array(true)
        );
    }

    /**
     * @dataProvider provideReorder
     */
    public function testReorder($before)
    {
        $this->requiresFeature(ModelInterface::FEATURE_REORDER);

        $this->model->reorder(
            '/test/menu/item2', 
            'subitem1',
            'subitem2',
            $before
        );

        $children = $this->model->getChildren('/test/menu/item2');
        $this->assertCount(2, $children);

        if ($before) {
            $this->assertEquals('/test/menu/item2/subitem1', $children[0]->getId());
        } else {
            $this->assertEquals('/test/menu/item2/subitem2', $children[0]->getId());
        }
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