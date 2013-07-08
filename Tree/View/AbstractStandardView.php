<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\View;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract class which processes requests in a standard way. This model
 * works for fronends which use specified endpoints for each action.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
abstract class AbstractStandardView implements ViewInterface
{
    public function processRequest(Request $request)
    {
        $command = $request->get('cmf_tree_ui_command');

        $commandMap = array(
            'children' => 'childrenResponse',
        );

        if (!isset($commandMap[$command])) {
            throw new \InvalidArgumentException(sprintf(
                'Command "%s" not recognized, I am only aware of "%s"',
                $command, implode(',', $commandMap)
            ));
        }

        return $this->{$commandMap[$command]}($request);
    }

    /**
     * Return children of a given node.
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    abstract public function childrenResponse(Request $request);
}
