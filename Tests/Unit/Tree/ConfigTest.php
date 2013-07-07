<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Config;

class ConfigTest extends \PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->config = new Config;
    }

    public function testArrayAccess()
    {
        $this->config->setDefaults(array(
            'foobar' => 'car'
        ));
        $this->config['foobar'] = 'bar';
        $this->assertEquals('bar', $this->config['foobar']);
    }

    /**
     * @depends testArrayAccess
     */
    public function testOptionsResolverDefaults()
    {
        $this->config->setDefaults(array(
            'foobar' => 'barfoo',
            'barfoo' => 'foobar',
        ));

        $this->assertEquals('foobar', $this->config['barfoo']);
    }
}
