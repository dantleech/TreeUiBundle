<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Controller;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class TreeControllerTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Component\Testing\DataFixtures\PHPCR\LoadBaseData',
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData',
        ));
    }

    public function testView()
    {
        $client = $this->createClient();
    }
}
