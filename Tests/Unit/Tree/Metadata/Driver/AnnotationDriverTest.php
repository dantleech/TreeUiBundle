<?php

namespace Symfony\Cmf\Bundle\TreeUi\Tests\Unit\Tree\Metadata\Driver;

use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Driver\AnnotationDriver;
use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Annotations\Node as NodeAnnotation;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->refl = new \ReflectionClass('Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tests\Resources\Document\Menu');

        $this->reader = $this->getMockBuilder(
            'Doctrine\Common\Annotations\AnnotationReader'
        )->disableOriginalConstructor()->getMock();
        $this->driver = new AnnotationDriver($this->reader);
        $this->annotation = new NodeAnnotation;
    }

    public function testLoadMetadataForClass()
    {
        $this->reader->expects($this->once())
            ->method('getClassAnnotation')
            ->will($this->returnValue($this->annotation));

        $expected = array(
            'getIdMethod' => 'getId',
            'getLabelMethod' => 'getTitle',
            'setLabelMethod' => 'setTitle',
            'classLabel' => 'Menu',
            'parentClasses' => array(
                'Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tests\Resources\Document\Menu',
            ),
            'childClasses' => array(
                'Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tests\Resources\Document\MenuNode',
            ),
            'childMode' => 'any',
            'icon' => '',
        );

        // setup the annotation node luckily it has the same
        // structure as the metadata...
        foreach ($expected as $key => $value) {
            $this->annotation->$key = $value;
        }

        $meta = $this->driver->loadMetadataForClass($this->refl);

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $meta->$key);
        }
    }
}

