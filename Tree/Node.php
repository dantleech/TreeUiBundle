<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use PHPCR\Util\PathHelper;

/**
 * Model agnostic node class which can be used
 * by the tree views.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Node
{
    protected $id;
    protected $label;
    protected $hasChildren;
    protected $classLabel;
    protected $classFqn;

    public function getId() 
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLabel() 
    {
        return $this->label;
    }

    public function getName()
    {
        return PathHelper::getNodeName($this->id);
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function hasChildren() 
    {
        return $this->hasChildren;
    }

    public function setHasChildren($hasChildren)
    {
        $this->hasChildren = $hasChildren;
    }

    public function getClassLabel() 
    {
        return $this->classLabel;
    }
    
    public function setClassLabel($classLabel)
    {
        $this->classLabel = $classLabel;
    }

    public function getClassFqn() 
    {
        return $this->classFqn;
    }
    
    public function setClassFqn($classFqn)
    {
        $this->classFqn = $classFqn;
    }
}
