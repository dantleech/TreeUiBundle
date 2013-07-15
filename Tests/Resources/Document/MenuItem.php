<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Annotations as CmfTreeUi;

/**
 * @PHPCRODM\Document()
 * @CmfTreeUi\Node(
 *    getIdMethod="getId",
 *    getLabelMethod="getTitle",
 *    setLabelMethod="setTitle",
 *    classLabel="Menu Item",
 *    childClasses={
 *      "Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Document\MenuItem"
 *    },
 *    icon="bundles/cmftreeuibundle/themes/famfamsilk/directory.png"
 *  )
 */
class MenuItem
{
    /**
     * @PHPCRODM\Id()
     */
    protected $id;

    /**
     * @PHPCRODM\String()
     */
    protected $title;

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

    public function __toString()
    {
        return $this->title;
    }
}

