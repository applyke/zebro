<?php

namespace Application\Factory\Controller;

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\UserController as Controller;

class UserFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ControllerManager $controllerManager */
        $serviceManager = $controllerManager->getServiceLocator();

        /** @var \Doctrine\Orm\EntityManager $entityManager */
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        $AuthenticationService = $serviceManager->get('Zend\Authentication\AuthenticationService');
        $controller = new Controller();
        $controller->setEntityManager($entityManager);
        $paginationService = $serviceManager->get('pagination');
        $controller->setPaginationService($paginationService);
        $controller->setAuthenticationService($AuthenticationService);
        return $controller;
    }
}
