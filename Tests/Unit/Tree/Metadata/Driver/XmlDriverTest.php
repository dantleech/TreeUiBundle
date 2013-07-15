<?php

namespace Symfony\Cmf\Bundle\TreeUi\Tests\Unit\Tree\Metadata\Driver;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Driver\XmlDriver;

class XmlDriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->locator = $this->getMock('Metadata\Driver\FileLocatorInterface');
        $this->file = __DIR__.'/config/Menu.xml';
        $this->refl = new \ReflectionClass('Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\Menu');
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
            'childClasses' => array(
                'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\Menu',
            ),
            'childMode' => 'include',
            'icon' => '',
        );

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $meta->$key);
        }
    }
}
