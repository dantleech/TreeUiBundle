<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Unit\Twig;

use Symfony\Cmf\Bundle\TreeUiBundle\Twig\CmfTreeUiExtension;

class CmfTreeUiExtensionTest extends \PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->factory = $this->getMockBuilder(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\TreeFactory'
        )->disableOriginalConstructor()->getMock();

        $this->ext = new CmfTreeUiExtension($this->factory);
        $this->tree = $this->getMockBuilder(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree'
        )->disableOriginalConstructor()->getMock();
        $this->view = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface');
    }

    public function provideFunctionRegistration()
    {
        return array(
            array('cmf_tree_ui_render'),
            array('cmf_tree_ui_javascripts'),
            array('cmf_tree_ui_stylesheets'),
        );
    }

    /**
     * Basic test to ensure that functions are registered correctly
     *
     * @dataProvider provideFunctionRegistration
     */
    public function testFunctionRegistration($name)
    {
        $funcs = $this->ext->getFunctions();
        $func = $funcs[$name]->compile();
    }

    public function testRenderTree()
    {
        $this->factory->expects($this->once())
            ->method('getTree')
            ->with('foo')
            ->will($this->returnValue($this->tree));
        $this->tree->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($this->view));
        $this->view->expects($this->once())
            ->method('getOutput')
            ->will($this->returnValue('some html'));

        $res = $this->ext->renderTree('foo');
        $this->assertEquals('some html', $res);
    }

    public function testGetStylesheets()
    {
        $this->factory->expects($this->once())
            ->method('getStylesheets')
            ->will($this->returnValue(array('style1')));

        $res = $this->ext->getStylesheets();

        $this->assertEquals(array('style1'), $res);
    }

    public function testGetJavascripts()
    {
        $this->factory->expects($this->once())
            ->method('getJavascripts')
            ->will($this->returnValue(array('javascript1')));

        $res = $this->ext->getJavascripts();

        $this->assertEquals(array('javascript1'), $res);
    }
}
