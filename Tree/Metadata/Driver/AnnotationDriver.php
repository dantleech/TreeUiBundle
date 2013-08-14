<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\TreeMetadata;
use Metadata\Driver\AdvancedDriverInterface;

class AnnotationDriver implements AdvancedDriverInterface
{
    protected $reader;
    protected $annotatedClasses;

    /**
     * @param AnnotationReader $reader           - AnnorationReader implementation
     * @param array            $annotatedClasses - List of all known annotated classes 
     *     (determined in DI builder)
     */
    public function __construct(AnnotationReader $reader, $annotatedClasses = array())
    {
        $this->reader = $reader;
        $this->annotatedClasses = $annotatedClasses;
    }

    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $annotation = $this->reader->getClassAnnotation(
            $class,
            'Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Annotations\Node'
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

        if (null !== $annotation->parentClasses) {
            $meta->parentClasses = $annotation->parentClasses;
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

    public function getAllClassNames()
    {
        return $this->annotatedClasses;
    }
}
