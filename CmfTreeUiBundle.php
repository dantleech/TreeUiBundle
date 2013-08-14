<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Cmf\Bundle\TreeUi\CoreBundle\DependencyInjection\Compiler\ConfigPass;

class CmfTreeUiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigPass);
        parent::build($container);
    }
}

