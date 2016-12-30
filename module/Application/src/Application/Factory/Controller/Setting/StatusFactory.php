<?php

namespace Application\Factory\Controller\Setting;

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\Setting\StatusController as Controller;

class StatusFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ControllerManager $controllerManager */
        $serviceManager = $controllerManager->getServiceLocator();

        /** @var \Doctrine\Orm\EntityManager $entityManager */
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        $controller = new Controller();
        $controller->setEntityManager($entityManager);
        $paginationService = $serviceManager->get('pagination');
        $controller->setPaginationService($paginationService);
        return $controller;
    }
}
