<?php

namespace Symfony\Cmf\Bundle\TreeUi\Tests\Unit\Tree\Metadata\Driver;

use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Driver\XmlDriver;

class XmlDriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = $this->getMock('Metadata\Driver\FileLocatorInterface');
        $this->file = __DIR__.'/config/MenuNode.xml';
        $this->refl = new \ReflectionClass('Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tests\Resources\Document\Menu');
    }

    public function testLoadMetadataFromFile()
    {
        $xmlDriver = new XmlDriver($this->locator);
        $meta = $xmlDriver->loadMetadataFromFile($this->refl, $this->file);

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
            'icon' => '',
        );

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $meta->$key);
        }
    }
}
