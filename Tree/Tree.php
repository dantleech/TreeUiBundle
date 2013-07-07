<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

class Tree
{
    protected $name;
    protected $view;
    protected $model;

    public function __construct(
        $name, 
        ModelInterface $model, 
        ViewInterface $view
    ) 
    {
        $this->view = $view;
        $this->view->setTree($this);

        $this->model = $model;
        $this->name = $name;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getName()
    {
        return $this->name;
    }
}
