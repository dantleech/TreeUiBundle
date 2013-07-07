<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class ConfigTest extends BaseTestCase
{
    public function testPrototype()
    {
        $configI1 = $this->getContainer()->get('cmf_tree_ui.config');
        $configI1->setDefaults(array('foo' => false));
        $configI1->set('foo', true);
        $this->assertTrue($configI1->get('foo'));
        $this->assertTrue($configI1->get('foo'));

        $configI2 = $this->getContainer()->get('cmf_tree_ui.config');
        $this->assertNotSame($configI1, $configI2);
    }
}

