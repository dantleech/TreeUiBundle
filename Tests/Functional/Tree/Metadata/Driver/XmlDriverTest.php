<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Metadata\Driver;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class TreeControllerTest extends BaseTestCase
{
    public function setUp()
    {
        $this->content = new Generic;
        $this->refl = new \ReflectionClass(get_class($this->content));
    }

    public function testLoadMetadataForClass()
    {
        $driver = $this->getContainer()->get('cmf_tree_ui.metadata.driver.xml');
        $meta = $driver->loadMetadataForClass($this->refl);

        $this->assertEquals('getId', $meta->idMethod);
        $this->assertEquals('getNodename', $meta->labelMethod);
    }
}

