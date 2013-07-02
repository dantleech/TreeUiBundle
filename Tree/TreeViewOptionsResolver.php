<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TreeViewOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        parent::__construct();
        $this->setDefaults(array(
            'select_node' => '/',
        ));
    }
}
