<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Model;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Metadata\MetadataFactory;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelConfig;

class PhpcrOdmModel implements ModelInterface
{
    protected $mr;
    protected $mdf;

    protected $managerName;

    protected $childrenMap;

    public function __construct(ManagerRegistry $mr, MetadataFactory $mdf) 
    {
        $this->mr = $mr;
        $this->mdf = $mdf;
    }

    public function configure(ModelConfig $config)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getFeatures()
    {
        return array(
            ModelInterface::FEATURE_GET_CHILDREN,
            ModelInterface::FEATURE_GET_ANCESTORS,
            ModelInterface::FEATURE_GET_NODE,
            ModelInterface::FEATURE_MOVE,
            ModelInterface::FEATURE_DELETE,
            ModelInterface::FEATURE_REORDER,
            ModelInterface::FEATURE_RENAME,
        );
    }

    /**
     * This function builds a map of valid children for
     * each mapped object.
     *
     * OPTIMIZE: This should be cached
     *
     * @return void
     */
    private function buildChildrenMap()
    {
        $allClasses = $this->mdf->getAllClassNames();
        $allClasses2 = $allClasses;
        $blacklist = array();

        // initialize the array
        foreach ($allClasses as $className) {
            $this->childrenMap[$className] = array();
        }

        // first pass: sort out parent classes, blacklisting from "any" lists
        //             if specified.
        foreach ($allClasses as $className) {
            $meta = $this->mdf->getMetadataForClass($className)->getOutsideClassMetadata();

            if ($meta->parentClasses) {
                // if class explicitly specifies parent classes, do
                // not show this class in "all" children lists.
                $blacklist[$meta->name] = true;
                foreach ($meta->parentClasses as $parentClass) {
                    if (!isset($this->childrenMap[$className][$parentClass])) {
                        $this->childrenMap[$parentClass][$meta->name] = $meta;
                    }
                }
            }
        }

        // second pass: sort out *include* and *any* - take into account blacklist
        foreach ($allClasses as $className) {
            $meta = $this->mdf->getMetadataForClass($className)->getOutsideClassMetadata();

            if ($meta->childMode == 'include') {
                if ($meta->childClasses) {
                    foreach ($meta->childClasses as $childClass) {
                        if (!isset($this->childrenMap[$className][$childClass])) {
                            $this->childrenMap[$className][$childClass] = $this->mdf->getMetadataForClass($childClass)->getOutsideClassMetadata();
                        }
                    }
                }
            } elseif ($meta->childMode == 'any') {
                foreach ($allClasses2 as $childClass) {
                    if (isset($blacklist[$childClass])) {
                        continue;
                    }

                    if (!isset($this->childrenMap[$className][$childClass])) {
                        $this->childrenMap[$className][$childClass] = $this->mdf->getMetadataForClass($childClass)->getOutsideClassMetadata();
                    }
                }
            }
        }
    }

    protected function getChildClasses($object)
    {
        if (empty($this->childrenMap)) {
            $this->buildChildrenMap();
        }

        $className = ClassUtils::getClass($object);

        return $this->childrenMap[$className];
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
        $node->setClassLabel($metadata->classLabel);
        $node->setClassFqn($metadata->name);
        $node->setChildClasses($this->getChildClasses($object));
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

    public function getAncestors($id)
    {
        $elements = explode('/', $id);
        array_shift($elements);
        $path = '';
        $pathStack = array();
        $ancestors = array();
        $ancestors[] = $this->createNode(
            $this->getDocument($path) // this doesn't work (path is ""!)
        );

        foreach ($elements as $element) {
            $pathStack[] = $element;
            $path = implode('/', $pathStack);
            $ancestors[] = $this->createNode(
                $this->getDocument($path)
            );
        }

        return $ancestors;
    }

    public function move($sourceId, $targetId)
    {
        $rootDoc = $this->getDocument($sourceId);
        $this->getDm()->move($rootDoc, $targetId);
        $this->getDm()->flush();
    }

    /**
     * Rename operation. This is required because the "label" field
     * exposed in the tree might not correspond to the underlying PHPCR
     * NodeName and so a move operation would be insufficient.
     *
     * @param object $document    an already registered document
     * @param string $newName     new name for node.
     */
    public function rename($nodeId, $newName)
    {
        $doc = $this->getDocument($nodeId);

        $metadata = $this->getMetadata($doc);
        $metadata->setLabel($doc, $newName);
        $this->getDm()->persist($doc);
        $this->getDm()->flush();
    }


    public function reorder($parentId, $sourceId, $targetId, $before = false)
    {
        $parentDoc = $this->getDocument($parentId);
        $this->getDm()->reorder($parentDoc, $sourceId, $targetId, $before);
        $this->getDm()->flush();
    }

    public function delete($nodeId)
    {
        $node = $this->getDocument($nodeId);
        $this->getDm()->remove($node);
        $this->getDm()->flush();
    }

    public function getNode($path)
    {
        $doc = $this->getDocument($path);
        $node = $this->createNode($doc);
        return $node;
    }
}
