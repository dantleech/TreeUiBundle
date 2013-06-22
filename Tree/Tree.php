<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

class Tree
{
    protected $view;
    protected $model;

    public function __construct(ModelInterface $model, ViewInterface $view)
    {
        $view->setModel($model);
        $this->view = $view;
        $this->model = $model;
    }

    public function getView()
    {
        return $this->view;
    }
}
