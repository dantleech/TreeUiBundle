<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata;

use Metadata\ClassMetadata;

class TreeMetadata extends ClassMetadata
{
    public $idMethod;
    public $labelMethod;

    public function getLabel($object)
    {
        return $object->{$this->labelMethod}();
    }

    public function getId($object)
    {
        return $object->{$this->idMethod}();
    }
}
