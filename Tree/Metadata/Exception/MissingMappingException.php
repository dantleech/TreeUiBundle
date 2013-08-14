<?php

namespace Symfony\Cmf\Bundle\TreeUi\CoreBundle\Tree\Metadata\Exception;

class MissingMappingException extends \RuntimeException
{
    public function __construct($class, $field)
    {
        $message = sprintf(
            'Apparently you have not provided a mapping for the field "%s" in class "%s"',
            $field,
            $class
        );

        parent::__construct($message);
    }
}
