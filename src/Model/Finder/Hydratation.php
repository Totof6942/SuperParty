<?php

namespace Model\Finder;

class Hydratation
{

    /**
     * @param Object $obejct 
     * @param mixed  $value
     * @param string $attribute
     */
    public function setAttributeValue($object, $value, $attribute)
    {
        $reflector = new \ReflectionClass(get_class($object));
        $property = $reflector->getProperty($attribute);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

}
