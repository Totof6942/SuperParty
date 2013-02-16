<?php

namespace Model\Finder;

class Hydratation
{

    /**
     * @param Object $object 
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

    /**
     * @param Obejct $object
     * @param array  $values (propertyName => value)
     */
    public function setObject($object, $values)
    {
        $reflector = new \ReflectionClass(get_class($object));
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            if (array_key_exists($property->name, $values)) {
                $doc =  $property->getDocComment();
                preg_match('#@var ([a-zA-Z]+) *.*\n#s', $doc, $annotations);

                if (2 === count($annotations)) {
                    if (!($values[$property->name] instanceof \DateTime) && ('datetime' === strtolower($annotations[1]))) {
                        $values[$property->name] = new \DateTime($values[$property->name]);
                    }
                }

                $property->setAccessible(true);
                $property->setValue($object, $values[$property->name]);
            }
        }
    }

}
