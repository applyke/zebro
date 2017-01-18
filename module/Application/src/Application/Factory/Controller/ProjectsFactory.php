<?php

namespace Application\Factory\Controller;

use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\ProjectsController as Controller;

class ProjectsFactory implements FactoryInterface
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
        $identityPlugin = $serviceManager->get('identity');
        $controller->setPaginationService($paginationService);
        $controller->setIdentityPlugin($identityPlugin);
        /** @var \Application\Service\DacService $dacService */
        $dacService = $serviceManager->get('dac');
        $controller->setDacService($dacService);
        return $controller;
    }
}
