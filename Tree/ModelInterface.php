<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelConfig;

interface ModelInterface extends FeaturableInterface
{
    public function getChildren($path);

    public function getAncestors($path);

    public function move($sourcePath, $targetPath);

    public function reorder($parentId, $sourceId, $targetId, $before = false);

    public function getNode($path);

    public function configure(ModelConfig $config);
}

