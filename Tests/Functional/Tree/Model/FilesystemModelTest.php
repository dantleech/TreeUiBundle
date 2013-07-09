<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class FilesystemModelTest extends BaseTest
{
    protected function getModel()
    {
        $model = $this->getContainer()->get('cmf_tree_ui.model.filesystem');
        $model->setRoot(__DIR__.'/filesystem');

        return $model;
    }
}
