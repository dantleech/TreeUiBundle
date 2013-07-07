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
}
