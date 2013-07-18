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

        if (isset($xmlMapping->children)) {
            $meta->childMode = (string) $xmlMapping->children['mode'];
        }

        $childClasses = array();

        foreach ($xmlMapping->children as $childrenEl) {
            foreach ($childrenEl->class as $classEl) {
                $childClasses[] = (string) $classEl;
            }
        }

        if ($childClasses) {
            $meta->childClasses = $childClasses;
        }

        return $meta;
    }
}
