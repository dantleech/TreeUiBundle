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
}
