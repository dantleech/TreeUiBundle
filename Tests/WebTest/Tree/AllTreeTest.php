<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\WebTest\Trees;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

class AllTreeTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));
    }

    public function provideTree()
    {
        return array(
            array('fancytree_phpcrodm'),
            array('dynatree_phpcrodm'),
            array('elfinder_filesystem'),
        );
    }

    /**
     * @dataProvider provideTree
     */
    public function testTree($treeName)
    {
        $client = $this->createClient();
        $client->request('get', '/tree/'.$treeName);
        $res = $client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
    }

    /**
     * @dataProvider provideTree
     */
    public function testTreeChildren($treeName)
    {
        $client = $this->createClient();
        $client->request('get', '/_cmf_tree_ui/fancytree_phpcrodm/children//');
        $res = $client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
    }
}
