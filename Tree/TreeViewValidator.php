<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;

/**
 * Simple utility class for managing features and
 * validating configurations depending on features.
 */
class TreeViewValidator
{
    protected $allFeatures = array(
        ViewInterface::FEATURE_BROWSE,
        ViewInterface::FEATURE_PRE_SELECT_NODE,
        ViewInterface::FEATURE_FORM_INPUT,
        ViewInterface::FEATURE_FORM_INPUT_MULTIPLE,
    );

    public function viewHasFeature(ViewInterface $view, $feature)
    {
        return in_array($feature, $view->getFeatures());
    }

    public function getAllFeatures()
    {
        return $this->allFeatures;
    }
}
