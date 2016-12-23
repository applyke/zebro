<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\ProjectService as Service;

class ProjectFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new Service();
        $phpRenderer = $serviceLocator->get('\Zend\View\Renderer\PhpRenderer');
        $instance->setRenderer($phpRenderer);
        return $instance;
    }
}