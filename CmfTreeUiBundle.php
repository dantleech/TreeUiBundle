<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Cmf\Bundle\TreeUiBundle\DependencyInjection\Compiler\AutoRoutePass;

class CmfTreeUiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}

