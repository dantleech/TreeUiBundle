<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\WebTest\Trees;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

class PhpcrOdmFancyTreeTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));
    }

    public function testTree()
    {
        $client = $this->createClient();
        $client->request('get', '/tree/phpcr-odm');
        $res = $client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
    }

    public function testTreeChildren()
    {
        $client = $this->createClient();
        $client->request('get', '/_cmf_tree_ui/children/default///children');
        $res = $client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
    }
}
