<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Identity extends AbstractPlugin
{
    /**
     * @return \Application\Entity\User|null
     * @throws \Exception
     */
    public function getIdentity()
    {
        if (!IS_LOGGED_IN) {
            return null;
        }
        $serviceLocator = $this->getController()->getServiceLocator();
        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
        $userId = (int)$authService->getIdentity();
        if (!$userId) {
            return null;
        }
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $user = $userRepository->findOneById($userId);
        if (!$user instanceof \Application\Entity\User) {
            throw new \Exception('User not found. Identity: ' . (int)$authService->getIdentity());
        }
        return $user;
    }
}