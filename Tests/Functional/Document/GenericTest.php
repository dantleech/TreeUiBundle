<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Document;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

/**
 * Test for generic document mapping
 */
class GenericTest extends BaseTestCase
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

        $this->assertEquals('getId', $meta->getIdMethod);
        $this->assertEquals('getNodename', $meta->getLabelMethod);
        $this->assertEquals('setNodename', $meta->setLabelMethod);
        $this->assertEquals('Generic', $meta->classLabel);
        $this->assertEquals(array(), $meta->childClasses);
        $this->assertEquals('any', $meta->childMode);
    }
}
