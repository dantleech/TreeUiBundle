<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Metadata\MetadataFactory;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;

class NodeFactory
{
    protected $mdf;

    public function __construct(MetadataFactory $mdf)
    {
        $this->mdf = $mdf;
    }

    protected function getMetadata($object)
    {
        $classFqn = ClassUtils::getClass($object);
        return $this->mdf->getMetadata($classFqn);
    }

    public function createNode($object)
    {
        $metadata = $this->getMetadata($object);

        $node = new Node;
        $node->setId($metadata->getId($object));
        $node->setLabel($metadata->getLabel($object));

        return $node;
    }
}
