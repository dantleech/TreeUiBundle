<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Unit\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\TreeFactory;

class TreeFactoryTest extends \PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->container = $this->getMockBuilder(
            'Symfony\Component\DependencyInjection\ContainerInterface'
        )->disableOriginalConstructor()->getMock();

        $this->tree1 = $this->getMockBuilder(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree'
        )->disableOriginalConstructor()->getMock();
        $this->tree1View = $this->getMock(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface'
        );

        $this->tree2 = $this->getMockBuilder(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree'
        )->disableOriginalConstructor()->getMock();
        $this->tree2View = $this->getMock(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface'
        );
        $this->tree2Model = $this->getMock(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface'
        );

        $this->factory = new TreeFactory($this->container);
    }

    public function provideGetTree()
    {
        return array(
            array(null, null),
            array(
                array('viewOpt1' => 'one'),
                null
            ),
            array(
                null,
                array('modelOpt1' => 'one')
            ),
            array(
                array('viewOpt1' => 'one'),
                array('modelOpt1' => 'one')
            ),
        );
    }

    /**
     * @dataProvider provideGetTree
     */
    public function testGetTree($modelOptions, $viewOptions)
    {
        $this->container->expects($this->once())
            ->method('get')
            ->with('foo.bar')
            ->will($this->returnValue($this->tree2));
        $this->tree2->expects($this->once())
            ->method('getModel')
            ->will($this->returnValue($this->tree2Model));
        $this->tree2->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($this->tree2View));

        if ($viewOptions !== null) {
            $this->tree2View->expects($this->once())
                ->method('mergeOptions')
                ->with($viewOptions === null ? array() : $viewOptions);
        }

        if ($modelOptions !== null) {
            $this->tree2Model->expects($this->once())
                ->method('mergeOptions')
                ->with($modelOptions === null ? array() : $modelOptions);
        }

        $this->factory->registerTreeServiceId('foobar', 'foo.bar');
        $res = $this->factory->getTree('foobar', $modelOptions, $viewOptions);
        $this->assertSame($this->tree2, $res);
    }

    public function provideGetAssets()
    {
        return array(
            array('Stylesheets', array(
                'tree1' => array(
                    'foobar1',
                    'foobar2',
                ),
                'tree2' => array(
                    'foobar1',
                    'foobar3',
                ),
            ), array('foobar1', 'foobar2', 'foobar3')),

            array('Javascripts', array(
                'tree1' => array(
                    'foobar1',
                    'foobar2',
                ),
                'tree2' => array(
                    'foobar1',
                    'foobar3',
                ),
            ), array('foobar1', 'foobar2', 'foobar3')),
        );
    }

    /**
     * @dataProvider provideGetAssets
     */
    public function testGetAssets($method, $trees, $expected)
    {
        $me = $this;
        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($id) use ($me) {
                return $me->{$id};
            }));

        foreach ($trees as $name => $assets) {
            $this->{$name}->expects($this->once())
                ->method('getView')
                ->will($this->returnValue($this->{$name.'View'}));

            $this->{$name.'View'}->expects($this->once())
                ->method('get'.$method)
                ->will($this->returnValue($assets));

            $this->factory->registerTreeServiceId($name, $name);
        }

        $res = $this->factory->{'get'.$method}('foobar');
        $this->assertEquals($expected, $res);
    }
}

