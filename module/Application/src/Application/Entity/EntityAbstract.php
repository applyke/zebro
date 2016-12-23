<?php

namespace Application\Entity;

abstract class EntityAbstract
{
    public function __call($method, $args)
    {
        if (0 === strpos($method, 'get')) {
            $property = $this->getProp($method);
            return $this->$property;
        } else if (0 === strpos($method, 'set')) {
            $property = $this->getProp($method);
            if (!property_exists($this, $property)) {
                throw new \Exception(get_class($this) . '::' . $property . ' property does not exists');
            }
            $this->$property = array_shift($args);
            return $this;
        }
        throw new \BadMethodCallException();
       
    }

    public function __isset($k)
    {
        return isset($this->$k);
    }

    protected function getProp($p)
    {
        $property = substr($p, 3);
        $propertyArr = preg_split('/(?=[A-Z])/', $property);
        unset($propertyArr[0]);
        $property = strtolower(implode('_', $propertyArr));
        return $property;
    }

}
