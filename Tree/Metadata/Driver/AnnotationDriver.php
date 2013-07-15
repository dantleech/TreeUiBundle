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

        if (!$annotation) {
            return null;
        }

        $this->checkMethodExists($class, $annotation->getLabelMethod);
        $this->checkMethodExists($class, $annotation->setLabelMethod);
        $this->checkMethodExists($class, $annotation->getIdMethod);

        $meta = new TreeMetadata($class->name);
        $meta->getLabelMethod = $annotation->getLabelMethod;;
        $meta->setLabelMethod = $annotation->setLabelMethod;;
        $meta->getIdMethod = $annotation->getIdMethod;
        $meta->classLabel = $annotation->classLabel;

        if (null !== $annotation->childClasses) {
            $meta->childClasses = $annotation->childClasses;
        }

        if (null !== $annotation->childMode) {
            $meta->childMode = $annotation->childMode;
        }

        return $meta;
    }

    private function checkMethodExists($class, $methodName)
    {
        if (!$class->hasMethod($methodName)) {
            throw new \RuntimeException(sprintf(
                'You have assigned a non-existant method "%s" on class "%s"',
                $methodName,
                $class->name
            ));
        }
    }
}
