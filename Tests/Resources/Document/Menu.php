<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Annotations as CmfTreeUi;

/**
 * @PHPCRODM\Document()
 * @CmfTreeUi\Node(
 *    validChildren={
 *      "\Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\MenuItem"
 *    },
 *    icon="bundles/cmftreeuibundle/themes/famfamsilk/directory.png",
 *    titleMethod="getTitle"
 *  )
 */
class Menu
{
    /**
     * @PHPCRODM\Id()
     */
    protected $id;

    /**
     * @PHPCRODM\String()
     */
    protected $title;

    /**
     * @PHPCRODM\Children()
     */
    protected $items;

    public function getId() 
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle() 
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getItems() 
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }

}
