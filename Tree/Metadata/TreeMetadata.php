<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata;

use Metadata\ClassMetadata;

class TreeMetadata extends ClassMetadata
{
    public function getId()
    {
        return $this->propertyMetadata['id']->getValue();
    }

    public function getLabel()
    {
        return $this->propertyMetadata['label']->getValue();
    }
}
