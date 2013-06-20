<?php

namespace Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\TreeMetadata;
use Symfony\Cmf\Bundle\TreeUiBundle\Tree\Metadata\Exception\MissingMappingException;

class ArrayDriver implements DriverInterface
{
    protected $classFqn;

    public function getMapping($classFqn)
    {
        if (!isset($this->mapping[$classFqn])) {
            throw new \RuntimeException(sprintf(
                'Class "%s" not mapped',
                $classFqn
            ));
        }

        return $this->mapping[$classFqn];
    }


    private function getAndValidateMethod($class, $mapping, $key)
    {
        if (!isset($mapping[$key])) {
            throw new MissingMappingException($class->name, 'label_method');
        }

        $labelMethod = $mapping[$key];

        if (!$class->hasMethod($labelMethod)) {
            throw new \RuntimeException(sprintf(
                'Class "%s" does not have method "%s"',
                $class->name,
                $labelMethod
            ));
        }

        return $labelMethod;
    }

    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $mapping = $this->getMapping($class->name);
        $idMethod = $this->getAndValidateMethod($mapping, 'id_method');
        $labelMethod = $this->getAndValidateMethod($mapping, 'label_method');

        $metadata = new TreeMetadata;
        $metadata->idMethod = $idMethod;
        $metadata->labelMethod = $labelMethod;

        return $metadata;
    }

    public function registerMapping($classFqn, $mapping)
    {
        $this->mappings[$classFqn] = $mapping;
    }
}
