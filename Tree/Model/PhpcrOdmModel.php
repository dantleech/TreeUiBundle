<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Model;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\NodeFactory;

class PhpcrOdmModel implements ModelInterface
{
    protected $mr;
    protected $nf;

    protected $managerName;

    public function __construct(ManagerRegistry $mr, NodeFactory $nf) 
    {
        $this->mr = $mr;
        $this->nf = $nf;
    }
    
    public function setManagerName($managerName)
    {
        $this->managerName = $managerName;
    }

    public function getOm()
    {
        return $this->mr->getManager($this->managerName);
    }

    public function getDocument($id)
    {
        $doc = $this->getOm()->find($id);

        if (!$doc) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot find document with path "%s" when trying to find its children.',
                $id
            ));
        }

        return $doc;
    }

    public function getChildren($id)
    {
        $children = array();

        $rootDoc = $this->getDocument($id);
        $children = $this->getOm()->getChildren($rootDoc);

        foreach ($children as $child) {
            $children[] = $this->nf->createNode($child);
        }

        return $children;
    }

    public function move($sourceId, $targetId)
    {
        $rootDoc = $this->getDocument($sourceId);
        $this->getOm()->move($rootDoc, $targetId);
    }


    public function reorder($parentId, $sourceId, $targetId, $before = false)
    {
        throw new \Exception('This probably will not work. SourceID is full path or just name??');

        $parentDoc = $this->getDocument($parentId);
        $this->getOm()->reorder($parentDoc, $sourceId, $targetId, $before);
    }

    public function getNode($path)
    {
        $doc = $this->getOm()->find(null, $path);
        $node = $this->nf->createNode($doc);
        return $node;
    }
}
