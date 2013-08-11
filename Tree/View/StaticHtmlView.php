<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * Static HTML view. This class is for testing purposes and
 * not intended for real world use.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class StaticHtmlView extends AbstractStandardView
{
    protected $twig;

    public function __construct(
        \Twig_Environment $twig
    )
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritDoc}
     */
    public function childrenResponse(Request $request)
    {
        return new Response('');
    }

    /**
     * {@inheritDoc}
     */
    public function moveResponse(Request $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function deleteResponse(Request $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function renameResponse(Request $request)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getFeatures()
    {
        return array(
            ViewInterface::FEATURE_BROWSE,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getOutput($output = array())
    {
        $children = $this->tree->getModel()->getChildren('/');

        return $this->twig->render('CmfTreeUiBundle:StaticHtmlView:list.html.twig', array(
            'children' => $children
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getJavascripts()
    {
        return array();
    }

    /**
     * {@inheritDoc}
     */
    public function getStylesheets()
    {
        return array();
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ViewConfig $config)
    {
    }
}
