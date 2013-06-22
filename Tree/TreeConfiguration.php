<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

class TreeConfiguration
{
    protected $basePath = '/';

    public function getBasePath() 
    {
        return $this->basePath;
    }
    
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }
}
