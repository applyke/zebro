<?php
namespace Application\Service;

use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class HydratorStrategyService implements HydratorInterface
{
    private $hydrator;

    public function __construct()
    {
        $this->hydrator = new ClassMethods();
    }

    public function extract($objects)
    {
        $data = $this->hydrator->extract($objects);
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        if (isset($data['send'])) {
            unset($data['send']);
        }
        $this->hydrator->hydrate($data, $object);
    }
}