<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Annotations as CmfTreeUi;

/**
 * @PHPCRODM\Document()
 * @CmfTreeUi\Node(
 *    getIdMethod="getId",
 *    getLabelMethod="getTitle",
 *    setLabelMethod="setTitle",
 *    classLabel="Menu",
 *    childMode="include",
 *    childClasses={
 *      "Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tests\Resources\Document\MenuItem"
 *    },
 *    icon="bundles/cmftreeuibundle/themes/famfamsilk/directory.png"
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
