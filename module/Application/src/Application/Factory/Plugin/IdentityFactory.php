<?php

namespace Application\Factory\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\Plugin\Identity as Plugin;

class IdentityFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new Plugin();
        $authService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
        $instance->setAuthService($authService);
        /** @var \Doctrine\Orm\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $instance->setEntityManager($entityManager);
        return $instance;
    }
}