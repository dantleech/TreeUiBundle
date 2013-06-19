<?php

namespace Symfony\Cmf\Bundle\TreeBrowserBundle\Tree;

interface ModelInterface
{
    public function getChildren($path);

    public function move($sourcePath, $targetPath);

    public function reorder($parentPath, $sourcePath, $targetPath, $before = false);
}

