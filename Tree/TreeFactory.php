<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

class TreeFactory
{
    protected $model;
    protected $view;

    public function registerTree($name, TreeModel $model, TreeView $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function getTree($name)
    {
        $tree = new Tree($model, $view);
        return $tree;
    }
}
