<?php

namespace Symfony\Cmf\Bundle\CmfTreeUiBundle\Tests\Unit\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree;

class TreeTest extends \PHPUnit_Framework_TestCase
{
    protected $tree;

    public function setUp()
    {
        $this->model = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface');
        $this->view = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface');
        $this->tree = new Tree('Foobar', $this->model, $this->view);
    }

    public function testGetSet()
    {
        $this->assertEquals('Foobar', $this->tree->getName());
    }
}
