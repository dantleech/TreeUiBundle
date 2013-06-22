<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\TreeMetadata;

class AnnotationDriver implements DriverInterface
{
    protected $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $annotation = $this->reader->getClassAnnotation(
            $class,
            'Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Annotations\Node'
        );

        $meta = new TreeMetadata($class->name);
        $meta->labelMethod = $annotation->labelMethod;;
        $meta->idMethod = $annotation->idMethod;;

        return $meta;
    }
}
