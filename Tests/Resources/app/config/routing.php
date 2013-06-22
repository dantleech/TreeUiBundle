<?php

use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();
$collection->addCollection(
    $loader->import('@CmfTreeUiBundle/Resources/config/routing/tree.yml')
);

return $collection;
