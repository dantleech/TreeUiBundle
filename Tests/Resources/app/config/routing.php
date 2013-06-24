<?php

use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();
$collection->addCollection(
    $loader->import('@CmfTreeUiBundle/Resources/config/routing/tree.yml')
);
$collection->addCollection(
    $loader->import('routing/test.yml')
);

return $collection;
