<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Node
{
    public $getIdMethod;
    public $getLabelMethod;
    public $setLabelMethod;
    public $icon;
    public $classLabel;
    public $childClasses;
    public $childMode;
}
