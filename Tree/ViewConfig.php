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
        ));

        $this->addFeatureDefaults(array(
            ViewInterface::FEATURE_CONTEXT_MENU => array(
                'context_menu.enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_RENAME => array(
                'context.rename.enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_COPY => array(
                'context.copy.enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_PASTE => array(
                'context.paste.enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_CUT => array(
                'context.cut.enable' => false,
            ),
            ViewInterface::FEATURE_CONTEXT_DELETE=> array(
                'context.delete.enable' => false,
                'context.delete.confirm' => true,
            ),
        ));
    }
}
