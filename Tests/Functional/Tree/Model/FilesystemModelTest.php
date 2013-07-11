<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Functional\Tree\Model;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ODM\PHPCR\Document\Generic;

class FilesystemModelTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
        $deleteFile = __DIR__.'/filesystem/test/menu/delete-me';
        if (!file_exists($deleteFile)) {
            file_put_contents($deleteFile, ''); 
        }
    }

    protected function getModel()
    {
        $model = $this->getContainer()->get('cmf_tree_ui.model.filesystem');
        $model->setRoot(__DIR__.'/filesystem');

        return $model;
    }
}
