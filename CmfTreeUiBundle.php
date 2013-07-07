<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Cmf\Bundle\TreeUiBundle\DependencyInjection\Compiler\ConfigPass;

class CmfTreeUiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigPass);
        parent::build($container);
    }
}

