<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Model;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;

/**
 * A TreeInterface implementation for the file system
 *
 * @author Uwe Jäger <uwe.jaeger@valiton.com>
 * @author Daniel Leech <daniel@dantleech.com>
 */
class FilesystemModel implements ModelInterface
{
    protected $root;
    protected $filesystem;

    /**
     * @param string $root
     * @param Filesystem $filesystem
     * @param CoreAssetsHelper $assetHelper
     */
    public function __construct(
        $root, 
        Filesystem $filesystem
    )
    {
        $this->root = $root;
        $this->filesystem = $filesystem;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ModelConfig $config)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getFeatures()
    {
        return array();
    }

    /**
     * Returns an array representation of children nodes of a node
     *
     * @param string $path The path of any PHPCR node
     * @return array children list
     */
    public function getChildren($path)
    {
        $iterator = new \DirectoryIterator($this->getRealPath($path));

        $nodes = array();
        foreach ($iterator as $info) {
            if (in_array($info->getFilename(), array('.', '..'))) {
                continue;
            }
            $nodes[] = $this->fileInfoToNode($info);
        }

        return $nodes;
    }

    /**
     * Move the node at $path to $target
     *
     * @param string $path
     * @param string $target
     * @return array the new id of the moved node at $target and the url_safe_id
     */
    public function move($path, $target)
    {
        throw new \Exception('Not implemented yet');

        $this->filesystem->rename($this->getRealPath($path), $this->getRealPath($target) . '/' . basename($path));
        return array(
            'id' => $target . '/' . basename($path),
            'url_safe_id' => ltrim($target, '/') . '/' . basename($path)
        );
    }

    /**
     * Reorder $moved (child of $parent) before or after $target
     *
     * @param string $parent the id of the parent
     * @param string $moved the id of the child being moved
     * @param string $target the id of the target node
     * @param bool $before insert before or after the target
     * @return void
     */
    public function reorder($parentId, $sourceId, $targetId, $before = false)
    {
        throw new \Exception('Not implemented yet');
    }

    public function getNode($path)
    {
        $path = $this->getRealPath($path);
        $info = new \SplFileInfo($path);
        $node = $this->fileInfoToNode($info);
        return $node;
    }

    public function getAncestors($id)
    {
        $elements = explode('/', $id);
        $path = '';
        $pathStack = array();
        $ancestors = array();

        foreach ($elements as $element) {
            $pathStack[] = $element;
            $path = implode('/', $pathStack);
            $ancestors[] = $this->fileInfoToNode(
                new \SplFileInfo($this->getRealPath($path))
            );
        }

        return $ancestors;
    }

    /**
     * Convert the file info to an array
     *
     * @param \SplFileInfo $info
     * @return array
     */
    protected function fileInfoToNode(\SplFileInfo $info)
    {
        $iPath = $info->getPath();
        $iFilename = $info->getFilename();

        // if this is the root, filename is just "/"
        if ($iPath.DIRECTORY_SEPARATOR.$iFilename == $this->root) {
            $iFilename = '';
        }

        $path = substr($iPath, strlen($this->root));

        $id = $path . '/' . $iFilename;

        $node = new Node;
        $node->setId($id);
        $node->setLabel($iFilename);
        $node->setHasChildren($info->isDir());

        return $node;
    }
//
//    public function createFolderAt($path)
//    {
//        $file = tempnam($this->getRealPath($path), 'Tree');
//        unlink($file);
//        mkdir($file);
//    }
//
//    public function createFileAt($path)
//    {
//        tempnam($this->getRealPath($path), 'Tree');
//    }
//
//    public function delete($path)
//    {
//        $this->filesystem->remove($this->getRealPath($path));
//    }
//
//    public function getProperties($path)
//    {
//        $info = new \SplFileInfo($this->getRealPath($path));
//        return array(
//            'Name' => $info->getFilename(),
//            'Size' => $info->getSize(),
//            'Created' => $info->getCTime()
//        );
//    }
//
    protected function getRealPath($path)
    {
        return $this->root . '/'. ltrim($path, '/');
    }
}
