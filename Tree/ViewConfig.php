<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ViewConfig extends Config
{
    public function configure()
    {
        $this->setDefaults(array(
            'select_node' => '/',
            'form_input' => false,
            'form_input_multiple' => false,
            'form_input_name' => 'cmf_tree_ui',
        ));
    }
}
