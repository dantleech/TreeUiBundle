<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\View;

use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Tree;

/**
 * Abstract class which processes requests in a standard way. This model
 * works for fronends which use specified endpoints for each action.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
abstract class AbstractStandardView implements ViewInterface
{
    protected $tree;

    public function processRequest(Request $request)
    {
        $command = $request->get('cmf_tree_ui_command');

        $commandMap = array(
            'children' => 'childrenResponse',
            'move' => 'moveResponse',
            'delete' => 'deleteResponse',
            'rename' => 'renameResponse',
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
     * Return response for children request
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    abstract public function childrenResponse(Request $request);

    /**
     * Return response for move request
     *
     * A move operation in this context involves the 
     * repositioning of a node. It MUST not be used
     * for renaming.
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    abstract public function moveResponse(Request $request);

    /**
     * Return response for delete request
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    abstract public function deleteResponse(Request $request);

    /**
     * Return response for rename request
     *
     * @return Symfony\Component\HttpFoundation\Response 
     */
    abstract public function renameResponse(Request $request);

    public function setTree(Tree $tree)
    {
        $this->tree = $tree;
    }
}
