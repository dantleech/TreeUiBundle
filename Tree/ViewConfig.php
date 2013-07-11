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
            ViewInterface::FEATURE_CONTEXT_RENAME => array(
                'context_rename_enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_COPY => array(
                'context_copy_enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_EDIT => array(
                'context_edit_enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_PASTE => array(
                'context_paste_enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_CUT => array(
                'context_cut_enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_DELETE=> array(
                'context_delete_enable' => false,
                'context_delete_confirm' => true,
            ),
        ));
    }
}
