<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Model;

use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Node;
use PHPCR\SessionInterface;
use PHPCR\NodeInterface;
use PHPCR\Util\PathHelper;
use PHPCR\PropertyInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\ModelConfig;

class PhpcrModel implements ModelInterface
{
    protected $session;

    public function __construct(SessionInterface $session) 
    {
        $this->session = $session;
    }

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

    protected function createNode(NodeInterface $phpcrNode)
    {
        $node = new Node;
        $node->setId($phpcrNode->getPath());
        $node->setLabel(PathHelper::getNodeName($phpcrNode->getPath()));
        $node->setHasChildren($phpcrNode->hasNodes());

        return $node;
    }

    protected function createPropertyNode($name, $parentId)
    {
        $node = new Node;
        $node->setId($parentId.'#'.$name);
        $node->setLabel($name);
        $node->setHasChildren(false);

        return $node;
    }
    
    
    public function getPhpcrNode($path)
    {
        $phpcrNode = $this->session->getNode($path);

        if (!$phpcrNode) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot find phpcrNodeument with path "%s".',
                $path
            ));
        }

        return $phpcrNode;
    }

    public function getChildren($id)
    {
        $children = array();

        $rootPhpcrNode = $this->getPhpcrNode($id);
        $children = $rootPhpcrNode->getNodes();

        $nodes = array();
        foreach ($children as $child) {
            $nodes[] = $this->createNode($child);
        }

        $properties = $rootPhpcrNode->getProperties();
        foreach ($properties as $name => $prop) {
            $nodes[] = $this->createPropertyNode($name, $id);
        }

        return $nodes;
    }

    public function getAncestors($id)
    {
        $elements = explode('/', $id);
        array_pop($elements);
        $ancestors = array();

        foreach ($elements as $element) {
            $pathStack[] = $element;
            if (!$path = implode('/', $pathStack)) {
                $path = '/';
            }
            $ancestors[] = $this->createNode(
                $this->getPhpcrNode($path)
            );
        }

        return $ancestors;
    }

    public function getNode($id)
    {
        return $this->createNode(
            $this->session->getNode($id)
        );
    }

    public function move($sourceId, $targetId)
    {
        throw new \Exception('Not supported yet');
    }


    public function reorder($parentId, $sourceId, $targetId, $before = false)
    {
        throw new \Exception('Not supported yet');
    }
}

