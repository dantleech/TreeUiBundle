<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tests\Resources\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('::index.html.twig');
    }

    public function phpcrOdmTreeAction(Request $request)
    {
        return $this->render('::tree/phpcrodm.html.twig');
    }
}

