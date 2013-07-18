<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Unit\Tree\Node\UrlGenerator;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node\UrlGenerator\DefaultUrlGenerator;

class DefaultUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->baseUrlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface'
        );
        $this->nodeUrlGenerator = new DefaultUrlGenerator($this->baseUrlGenerator);
        $this->node = $this->getMock(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node'
        );
    }

    public function testForTreeNode()
    {
        $this->baseUrlGenerator->expects($this->once())
            ->method('generate')
            ->with('_cmf_tree_ui_noop', array(
                'cmf_tree_ui_tree_name' => 'footree',
                'cmf_tree_ui_node_id' => '/tree/bar',
            ))
            ->will($this->returnValue('foo_url'));

        $this->node->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('/tree/bar'));

        $this->nodeUrlGenerator->fromTreeNode('noop', 'footree', $this->node);
    }
}
