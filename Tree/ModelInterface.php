<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelConfig;

interface ModelInterface extends FeaturableInterface
{
    const FEATURE_GET_CHILDREN = 'get_children';
    const FEATURE_GET_ANCESTORS = 'get_ancestors';
    const FEATURE_REORDER = 'reorder';
    const FEATURE_MOVE = 'move';
    const FEATURE_RENAME = 'rename';
    const FEATURE_DELETE = 'delete';
    const FEATURE_GET_NODE = 'get_node';

    public function getChildren($path);

    public function getAncestors($path);

    public function move($sourcePath, $targetPath);

    public function rename($nodePath, $newName);

    public function reorder($parentId, $sourceId, $targetId, $before = false);

    public function delete($nodeId);

    public function getNode($path);

    public function configure(ModelConfig $config);
}

