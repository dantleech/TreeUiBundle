<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TreeUiType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choices = array();

        $resolver->setDefaults(array(
            'options' => array(
                'form_input' => true,
                'form_input_multiple' => false,
            ),
        ));

        $resolver->setRequired(array(
            'tree_name',
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tree_name'] = $options['tree_name'];
        $view->vars['options'] = $options['options'];
    }

    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cmf_tree_ui_tree';
    }
}
