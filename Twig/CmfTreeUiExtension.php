<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Twig;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Factory;

class CmfTreeUiExtension extends \Twig_Extension
{
    protected $tf;

    public function __construct(Factory $tf)
    {
        $this->tf = $tf;
    }

    /**
     * Get list of available functions
     *
     * @return array
     */
    public function getFunctions()
    {
        $functions = array(
            'cmf_tree_ui_render' => new \Twig_Function_Method(
                $this, 'renderTree', array(
                    'is_safe' => array(
                        'html',
                        'javascript',
                    )
                )
            ),
            'cmf_tree_ui_javascripts' => new \Twig_Function_Method(
                $this, 'getJavascripts'
            ),
            'cmf_tree_ui_stylesheets' => new \Twig_Function_Method(
                $this, 'getStylesheets'
            ),
        );

        return $functions;
    }

    /**
     * Get the extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'cmf_tree_ui';
    }

    public function renderTree($name, $options = array())
    {
        $tree = $this->tf->getTree($name);
        return $tree->getView()->getOutput($options);
    }

    public function getStylesheets()
    {
        return $this->tf->getStylesheets();
    }

    public function getJavascripts()
    {
        return $this->tf->getJavascripts();
    }
}
