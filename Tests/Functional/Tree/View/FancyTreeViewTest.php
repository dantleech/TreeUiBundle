<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\View;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

// @todo: Refactor the generalized logic to base class -- all views should
//        follow basically the same test procedure.
class FancyTreeViewTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));

        $this->model = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface');

        $this->tree = $this->getMockBuilder('Symfony\Cmf\Bundle\TreeUiBundle\Tree\Tree')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tree->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue($this->model));

        $this->view = $this->getContainer()->get('cmf_tree_ui.view.fancy_tree');
        $this->view->setTree($this->tree);

        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->node1 = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node');
        $this->node2 = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node');
        $this->node3 = $this->getMock('Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node');
    }

    public function testGetChildrenResponse()
    {
        $this->request->expects($this->once())
            ->method('get')
            ->with('cmf_tree_ui_node_id')
            ->will($this->returnValue('/'));

        $this->model->expects($this->once())
            ->method('getChildren')
            ->with('/')
            ->will($this->returnValue(array($this->node2, $this->node3)));

        $this->node2->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue('Node 1'));
        $this->node2->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('/path/to/this/node'));

        $this->tree->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foobar_Tree'));

        $res = $this->view->childrenResponse($this->request);
        $obj = json_decode($res->getContent(), true);
        $this->assertCount(2, $obj);

        $node2 = array_shift($obj);

        $this->assertEquals(array(
            'title' => 'Node 1',
            'key' => '/path/to/this/node',
            'lazy' => null,
            'folder' => null,
            'tooltip' => null,
            'children_url' => '/_cmf_tree_ui/foobar_Tree/children//path/to/this/node',
            'move_url' => '/_cmf_tree_ui/foobar_Tree/move//path/to/this/node',
            'delete_url' => '/_cmf_tree_ui/foobar_Tree/delete//path/to/this/node',
            'rename_url' => '/_cmf_tree_ui/foobar_Tree/rename//path/to/this/node',
        ), $node2);
    }

    public function provideMoveResponse()
    {
        return array(
            array('before', '/target/node/foobar'),
            array('after', '/target/node/foobar'),
            array('over', '/target/node/barfoo/foobar'),
        );
    }

    /**
     * @dataProvider provideMoveResponse
     */
    public function testMoveResponse($mode, $moveTarget)
    {
        $this->request->expects($this->exactly(3))
            ->method('get')
            ->will($this->returnCallback(function ($key) use ($mode) {
                switch ($key) {
                    case 'cmf_tree_ui_node_id':
                        return '/some/node/foobar';
                    case 'cmf_tree_ui_target_node_id':
                        return '/target/node/barfoo';
                    case 'cmf_tree_ui_target_mode':
                        return $mode;
                }
            }));

        $this->model->expects($this->once())
            ->method('move')
            ->with('/some/node/foobar', $moveTarget);

        if ($mode != 'over') {
            $this->model->expects($this->once())
                ->method('reorder')
                ->with('/target/node', 'foobar', 'barfoo', $mode == 'before');
        }

        $this->view->moveResponse($this->request);
    }

    public function testRenameResponse()
    {
        $this->request->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnCallback(function ($key) {
                switch ($key) {
                    case 'cmf_tree_ui_node_id':
                        return '/some/node/foobar';
                    case 'cmf_tree_ui_new_name':
                        return 'barfoo';
                }
            }));

        $this->model->expects($this->once())
            ->method('rename')
            ->with('/some/node/foobar', 'barfoo');

        $this->view->renameResponse($this->request);
    }
}
