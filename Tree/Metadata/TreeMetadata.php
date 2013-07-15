<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata;

use Metadata\ClassMetadata;

class TreeMetadata extends ClassMetadata
{
    public $getIdMethod = 'getId';
    public $getLabelMethod = '__toString';
    public $setLabelMethod = null;
    public $classLabel;
    public $childClasses = array();
    public $childMode = 'include';
    public $icon;

    public function setChildMode($mode)
    {
        $validModes = array('include', 'any');
        if (!in_array($mode, $validModes)) {
            throw new \InvalidArgumentException('ChildMode must be one of (%s), "%s" given.',
                $mode, implode(',', $validModes)
            );
        }
        $this->childMode = $mode;
    }

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
        return $object->{$this->getIdMethod}();
    }
}
