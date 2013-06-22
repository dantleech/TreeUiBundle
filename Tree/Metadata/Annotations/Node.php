<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Node
{
    public $validChildren;
    public $icon;
    public $titleMethod;
}
