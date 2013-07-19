<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Metadata\Driver;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class AnnotationDriverTest extends BaseTestCase
{
    /**
     * @var \Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Driver\AnnotationDriver
     */
    protected $driver;

    public function setUp()
    {
        parent::setUp();
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures\LoadTreeData'
        ));

        $this->driver = $this->getContainer()->get('cmf_tree_ui.metadata.driver.annotation');
    }

    public function testGetAllClassNames()
    {
        $expected = array(
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\Menu',
            'Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\MenuItem',
        );

        $start = microtime(true);
        $classNames = $this->driver->getAllClassNames();
        $end = microtime(true);

        $this->assertCount(2, $classNames);
        $this->assertEquals($expected, $classNames);
    }
}

