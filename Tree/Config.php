<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Config extends OptionsResolver
{
    protected $userOptions = array();
    protected $resolved = false;
    protected $features;

    public function __construct($userOptions = array(), FeaturableInterface $featurable)
    {
        parent::__construct();
        $this->userOptions = $userOptions;
        $this->features = $featurable->getFeatures();
        $this->configure();
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->features);
    }

    protected function addFeatureDefaults($featureDefaults)
    {
        $newDefaults = array();
        foreach ($featureDefaults as $featureName => $defaults) {
            if ($this->hasFeature($featureName)) {
                $newDefaults = array_merge($newDefaults, $defaults);
            }
        }

        $this->setDefaults($newDefaults);
    }

    protected function configure()
    {
    }

    public function getOptions($options = array())
    {
        $options = array_merge(
            $this->userOptions,
            $options
        );

        return $this->resolve($options);
    }
}
