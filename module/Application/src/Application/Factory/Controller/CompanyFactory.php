<?php

namespace Application\Factory\Controller;

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\CompanyController as Controller;

class CompanyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ControllerManager $controllerManager */
        $serviceManager = $controllerManager->getServiceLocator();

        /** @var \Doctrine\Orm\EntityManager $entityManager */
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        $controller = new Controller();
        $controller->setEntityManager($entityManager);
        $identityPlugin = $serviceManager->get('identity');
        $paginationService = $serviceManager->get('pagination');
        $controller->setPaginationService($paginationService);
        $controller->setIdentityPlugin($identityPlugin);
        return $controller;
    }
}
