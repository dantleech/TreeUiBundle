<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Test;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

abstract class TreeWebTestCase extends BaseTestCase
{
    protected function getTreeName()
    {
        return 'default';
    }

    public function testTree()
    {
        $client = $this->createClient();
        $client->request('get', '/tree/'.$this->getTreeName());
        $res = $client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
    }

    public function testTreeChildren()
    {
        $client = $this->createClient();
        $client->request('get', '/_cmf_tree_ui/'.$this->getTreeName().'/children//');
        $res = $client->getResponse();
        $this->assertEquals(200, $res->getStatusCode());
    }
}
