<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\Menu;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\MenuItem;

class LoadTreeData implements DependentFixtureInterface, FixtureInterface
{
    public function getDependencies()
    {
        return array(
            'Symfony\Cmf\Component\Testing\DataFixtures\PHPCR\LoadBaseData',
        );
    }

    public function load(ObjectManager $dm)
    {
        $menu = new Menu;
        $menu->setId('/test/menu');
        $menu->setTitle('Menu 1');
        $dm->persist($menu);

        $item1 = new MenuItem;
        $item1->setId('/test/menu/item1');
        $item1->setTitle('Item 1');
        $dm->persist($item1);

        $deleteMe = new MenuItem;
        $deleteMe->setId('/test/menu/delete-me');
        $deleteMe->setTitle('Delete Me');
        $dm->persist($deleteMe);

        $item2 = new MenuItem;
        $item2->setId('/test/menu/item2');
        $item2->setTitle('Item 2');
        $dm->persist($item2);

        $item21 = new MenuItem;
        $item21->setId('/test/menu/item2/subitem1');
        $item21->setTitle('Item 2.1');
        $dm->persist($item21);

        $item22 = new MenuItem;
        $item22->setId('/test/menu/item2/subitem2');
        $item22->setTitle('Item 2.2');
        $dm->persist($item22);

        $item3 = new MenuItem;
        $item3->setId('/test/menu/item3');
        $item3->setTitle('Item 3');
        $dm->persist($item3);

        $item4 = new MenuItem;
        $item4->setId('/test/menu/item4');
        $item4->setTitle('Item 4');
        $dm->persist($item4);

        $dm->flush();
    }
}

