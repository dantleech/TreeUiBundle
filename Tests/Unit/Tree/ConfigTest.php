<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Config;

class ConfigTest extends \PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->featurable = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\FeaturableInterface');
    }

    protected function getConfig($userOptions = array())
    {
        return new Config($userOptions, $this->featurable);
    }

    public function testOptionDefaults()
    {
        $config = $this->getConfig();

        $config->setDefaults(array(
            'foobar' => 'barfoo',
            'barfoo' => 'foobar',
        ));

        $options = $config->getOptions();
        $this->assertEquals('foobar', $options['barfoo']);
    }

    public function testOptionUserOptions()
    {
        $config = $this->getConfig();

        $config->setDefaults(array(
            'foobar' => 'barfoo',
            'barfoo' => 'foobar',
        ));

        $options = $config->getOptions(array(
            'foobar' => 'zzzzz',
        ));

        $this->assertEquals('zzzzz', $options['foobar']);
    }

    public function testAddFeatureDefaults()
    {
        $this->featurable->expects($this->once())
            ->method('getFeatures')
            ->will($this->returnValue(array(
                'foobar'
            )));

        $testConfig = new TestConfigClass(array(), $this->featurable);
        $options = $testConfig->getOptions();

        $this->assertFalse(isset($options['key1']));
        $this->assertFalse(isset($options['key2']));
        $this->assertTrue(isset($options['key3']));
        $this->assertTrue(isset($options['key4']));


    }
}

class TestConfigClass extends Config
{
    protected function configure()
    {
        $this->addFeatureDefaults(array(
            'barfoo' => array(
                'key1' => 'val1',
                'key2' => 'val2',
            ),
            'foobar' => array(
                'key3' => 'val3',
                'key4' => 'val4',
            ),
        ));
    }
}
