<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Doctrine\Bundle\PHPCRBundle\Form\DataTransformer\DocumentToPathTransformer;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;

class TreeUiType extends AbstractType
{
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultOptions = array(
            'form_input' => true,
            'form_input_multiple' => false,
        );

        $resolver->setDefaults(array(
            'manager_name' => null,
            'options' => $defaultOptions,
        ));

        // merge tree options with defaults defined above
        $resolver->setNormalizers(array(
            'options' => function (Options $options, $value) use ($defaultOptions) {
                return array_merge($defaultOptions, $value);
            },
        ));

        $resolver->setRequired(array(
            'tree_name',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dm = $this->registry->getManager($options['manager_name']);
        $transformer = new DocumentToPathTransformer($dm);
        $builder->addModelTransformer($transformer);
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
