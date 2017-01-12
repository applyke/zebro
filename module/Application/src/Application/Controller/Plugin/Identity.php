<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Application\ApplicationTraits\IdentityAwareTrait;

class Identity extends AbstractPlugin
{

    use IdentityAwareTrait;

    /**
     * @return \Application\Entity\User|null
     * @throws \Exception
     */

    public function getIdentity()
    {
        if (!IS_LOGGED_IN) {
            return null;
        }
        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService = $this->getAuthService();
        $userId = (int)$authService->getIdentity();
        if (!$userId) {
            return null;
        }
        $entityManager = $this->getEntityManager();
        $userRepository = $entityManager->getRepository('\Application\Entity\User');
        $user = $userRepository->findOneById($userId);
        if (!$user instanceof \Application\Entity\User) {
            throw new \Exception('User not found. Identity: ' . (int)$authService->getIdentity());
        }
        return $user;
    }

    public function setEntityManager(\Doctrine\Orm\EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }
}