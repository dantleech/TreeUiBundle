<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Config extends OptionsResolver
{
    protected $userOptions = array();
    protected $resolved = false;
    protected $featurable;

    public function __construct($userOptions = array(), FeaturableInterface $featurable)
    {
        parent::__construct();
        $this->userOptions = $userOptions;
        $this->featurable = $featurable;
        $this->configure();
    }

    public function configure()
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
