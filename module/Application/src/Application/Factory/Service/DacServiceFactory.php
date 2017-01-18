<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Application\Service\DacService as Service;

class DacServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\Orm\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        /** @var \Zend\Log\Logger $logger */
        $logger = $serviceLocator->get('logger');
        $instance = new Service();
        $instance->setEntityManager($entityManager);
        $instance->setLogger($logger);
//        $config = $serviceLocator->get('config');
//        /** @var \Zend\Mvc\Application $application */
//        $application = $serviceLocator->get('application');
//        $instance->initialize($config, $application);
        return $instance;
    }
}