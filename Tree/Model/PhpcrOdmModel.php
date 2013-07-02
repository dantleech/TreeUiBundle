<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Model;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Metadata\MetadataFactory;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;

class PhpcrOdmModel implements ModelInterface
{
    protected $mr;
    protected $mdf;

    protected $managerName;

    public function __construct(ManagerRegistry $mr, MetadataFactory $mdf) 
    {
        $this->mr = $mr;
        $this->mdf = $mdf;
    }

    protected function getMetadata($object)
    {
        $classFqn = ClassUtils::getClass($object);
        $meta = $this->mdf->getMetadataForClass($classFqn);

        if (null === $meta) {
            throw new \Exception(sprintf(
                'Cannot get metadata for class "%s"',
                $classFqn
            ));
        }

        return $meta->getOutsideClassMetadata();
    }

    protected function createNode($object)
    {
        $metadata = $this->getMetadata($object);
        $phpcrNode = $this->getDm()->getNodeForDocument($object);

        $node = new Node;
        $node->setId($metadata->getId($object));
        $node->setLabel($metadata->getLabel($object));
        $node->setHasChildren($phpcrNode->hasNodes());

        return $node;
    }
    
    public function setManagerName($managerName)
    {
        $this->managerName = $managerName;
    }

    public function getDm()
    {
        return $this->mr->getManager($this->managerName);
    }

    public function getDocument($path)
    {
        $doc = $this->getDm()->find(null, $path);

        if (!$doc) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot find document with path "%s".',
                $path
            ));
        }

        return $doc;
    }

    public function getChildren($id)
    {
        $children = array();

        $rootDoc = $this->getDocument($id);
        $children = $this->getDm()->getChildren($rootDoc);

        $nodes = array();
        foreach ($children as $child) {
            $nodes[] = $this->createNode($child);
        }

        return $nodes;
    }

    public function move($sourceId, $targetId)
    {
        $rootDoc = $this->getDocument($sourceId);
        $this->getDm()->move($rootDoc, $targetId);
    }


    public function reorder($parentId, $sourceId, $targetId, $before = false)
    {
        throw new \Exception('This probably will not work. SourceID is full path or just name??');

        $parentDoc = $this->getDocument($parentId);
        $this->getDm()->reorder($parentDoc, $sourceId, $targetId, $before);
    }

    public function getNode($path)
    {
        $doc = $this->getDocument($path);
        $node = $this->createNode($doc);
        return $node;
    }
}
