<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;

class ViewConfig extends Config
{
    protected function configure()
    {
        $this->setDefaults(array(
            'select_node' => '/',
            'form_input' => false,
            'form_input_multiple' => false,
            'form_input_name' => 'cmf_tree_ui',
            'translation_domain' => 'CmfTreeUi',
        ));

        $this->addFeatureDefaults(array(
            ViewInterface::FEATURE_CONTEXT_MENU => array(
                'context_menu_enable' => false,
            ),
        ));
    }
}
