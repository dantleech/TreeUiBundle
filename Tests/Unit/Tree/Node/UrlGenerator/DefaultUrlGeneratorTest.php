<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Unit\Tree\Node\UrlGenerator;

use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Node\UrlGenerator\DefaultUrlGenerator;

class DefaultUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->baseUrlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface'
        );
        $this->nodeUrlGenerator = new DefaultUrlGenerator($this->baseUrlGenerator);
        $this->node = $this->getMock(
            'Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Node'
        );
    }

    public function testChildren()
    {
        $this->baseUrlGenerator->expects($this->once())
            ->method('generate')
            ->with('_cmf_tree_ui_children', array(
                'cmf_tree_ui_tree_name' => 'footree',
                'cmf_tree_ui_node_id' => '/tree/bar',
            ))
            ->will($this->returnValue('foo_url'));

        $this->node->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('/tree/bar'));

        $this->nodeUrlGenerator->children('footree', $this->node);
    }

    public function testCreateHtml()
    {
        $this->baseUrlGenerator->expects($this->once())
            ->method('generate')
            ->with('_cmf_tree_ui_create_html', array(
                'cmf_tree_ui_tree_name' => 'footree',
                'cmf_tree_ui_node_id' => '/tree/bar',
                'child_class_name' => '/Abb/Baa',
            ))
            ->will($this->returnValue('foo_url'));

        $this->node->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('/tree/bar'));

        $this->nodeUrlGenerator->createHtml('footree', $this->node, '/Abb/Baa');
    }
}
