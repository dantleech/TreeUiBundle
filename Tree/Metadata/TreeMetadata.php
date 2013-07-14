<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata;

use Metadata\ClassMetadata;

class TreeMetadata extends ClassMetadata
{
    public $idMethod = 'getId';
    public $getLabelMethod = '__toString';
    public $setLabelMethod = null;

    public function getLabel($object)
    {
        return $object->{$this->getLabelMethod}();
    }

    public function setLabel($object, $label)
    {
        return $object->{$this->setLabelMethod}($label);
    }

    public function getId($object)
    {
        return $object->{$this->idMethod}();
    }
}
