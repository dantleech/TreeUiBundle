<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

class Tree
{
    protected $view;

    public function __construct(ModelInterface $model, ViewInterface $view)
    {
        $this->view->setModel($model);
    }

    public function getView()
    {
        return $this->view;
    }
}
