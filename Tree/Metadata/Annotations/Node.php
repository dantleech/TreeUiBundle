<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Node
{
    public $idMethod;
    public $getLabelMethod;
    public $setLabelMethod;
    public $validChildren;
    public $icon;
}
