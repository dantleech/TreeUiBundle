<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;

class PhpcrModelTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));
    }

    protected function getModel()
    {
        return $this->getContainer()->get('cmf_tree_ui.model.phpcr');
    }

    // override this test as we also return the node properties atm,
    // so the actual number of nodes is different.
    public function testGetChildren()
    {
        $this->requiresFeature(ModelInterface::FEATURE_GET_CHILDREN);

        $nodes = $this->model->getChildren('/');
        $this->assertCount(2, $nodes);

        $nodes = $this->model->getChildren('/test');
        $this->assertCount(3, $nodes);

        $nodes = $this->model->getChildren('/test/menu');
        $this->assertCount(9, $nodes);
    }
}
