<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

interface ModelInterface
{
    public function getChildren($path);

    public function move($sourcePath, $targetPath);

    public function reorder($parentId, $sourceId, $targetId, $before = false);

    public function getNode($path);
}

