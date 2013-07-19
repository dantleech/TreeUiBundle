<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\TreeMetadata;
use Metadata\Driver\AbstractFileDriver;

class XmlDriver extends AbstractFileDriver implements DriverInterface
{
    public function getExtension()
    {
        return 'xml';
    }

    public function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $meta = new TreeMetadata($class->name);
        $xml = simplexml_load_file($file);

        if (count($xml->children()) > 1) {
            throw new \Exception(sprintf(
                'Only one mapping allowed per class in file "%s',
                $file
            ));
        }

        if (count($xml->children()) == 0) {
            throw new \Exception(sprintf('No mapping in file "%s"', $file));
        }

        $xmlMapping = $xml->children();
        $xmlMapping = $xmlMapping->{'tree-object'};

        $meta->getLabelMethod = (string) $xmlMapping['get-label-method'][0];
        $meta->setLabelMethod = (string) $xmlMapping['set-label-method'][0];
        $meta->idMethod = (string) $xmlMapping['id-method'][0];
        $meta->classLabel = (string) $xmlMapping['class-label'][0];
        $meta->childMode = (string) $xmlMapping['child-mode'][0];

        $childClasses = array();

        foreach ($xmlMapping->child as $childEl) {
            $childClasses[] = (string) $childEl;
        }

        if ($childClasses) {
            $meta->childClasses = $childClasses;
        }


        $parentClasses = array();

        foreach ($xmlMapping->parent as $parentEl) {
            $parentClasses[] = (string) $parentEl;
        }

        if ($parentClasses) {
            $meta->parentClasses = $parentClasses;
        }

        return $meta;
    }
}
