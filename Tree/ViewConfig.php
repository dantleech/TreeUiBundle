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

        $this->addFeatureDefaults(array(
            ModelInterface::FEATURE_CONTEXT_MENU => array(array(
                'context_menu.enable' => false,
            )),
            ModelInterface::FEATURE_CONTEXT_RENAME => array(array(
                'context_rename.enable' => false,
            )),
            ModelInterface::FEATURE_CONTEXT_COPY => array(array(
                'context_copy.enable' => false,
            )),
            ModelInterface::FEATURE_CONTEXT_PASTE => array(array(
                'context_paste.enable' => false,
            )),
            ModelInterface::FEATURE_CONTEXT_CUT => array(array(
                'context_cut.enable' => false,
            )),
            ModelInterface::FEATURE_CONTEXT_DELETE=> array(array(
                'context_delete.enable' => false,
                'context_delete.confirm' => true,
            )),
        ));
    }
}
