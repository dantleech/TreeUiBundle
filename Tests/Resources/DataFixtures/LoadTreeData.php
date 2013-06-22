<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\Menu;

class LoadTreeData implements FixtureInterface
{
    public function load(ObjectManager $dm)
    {
        $menu = new Menu;
        $menu->setId('/test/foobar');
        $menu->setTitle('Menu 1');
        $dm->persist($menu);
        $dm->flush();
    }
}

