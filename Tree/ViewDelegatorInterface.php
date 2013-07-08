<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree;

use Symfony\Component\HttpFoundation\Request;

/**
 * Model with an endpoint which delegates to
 * other methods. For use with frontends which use
 * only a single endpoint and pass a command in the 
 * query string.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface ViewDelegatorInterface extends ViewInterface
{
    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getDelegatedResponse(Request $request);
}
