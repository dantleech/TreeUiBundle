<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class PhpcrOdmModelTest extends BaseTest
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
        return $this->getContainer()->get('cmf_tree_ui.model.phpcr_odm');
    }

    public function testMenuChildClasses()
    {
        $node = $this->model->getNode('/test/menu');
        $ns = 'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document';

        // test child classes
        $childClasses = $node->getChildClasses();
        $this->assertTrue(
            isset($childClasses[$ns . '\MenuItem'])
        );
        $this->assertInstanceOf(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\TreeMetadata', 
            $childClasses[$ns . '\MenuItem']
        );
    }

    public function testGenericChildClasses()
    {
        // Generic node should NOT list MenuItem because MenuItem
        // specifies that it can only be child of Menu.
        $node = $this->model->getNode('/test');

        // test child classes
        $childClasses = $node->getChildClasses();
        $expectedTypes = array('Menu', 'Generic');

        foreach ($childClasses as $meta) {
            $this->assertContains($meta->classLabel, $expectedTypes);
        }
    }
}
