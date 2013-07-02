<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

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
    
}
