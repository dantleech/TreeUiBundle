<?php

namespace Symfony\Cmf\Bundle\CmfTreeUiBundle\Tests\Unit\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\TreeViewOptionsResolver;

class TreeViewOptionsResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $tree;

    public function setUp()
    {
        $this->tree = new TreeViewOptionsResolver();
    }

    public function testDefaults()
    {
        $options = $this->tree->resolve();
        $this->assertEquals('/', $options['select_node']);
    }
}

