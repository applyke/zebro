<?php

namespace Application\ApplicationTraits;

trait IdentityAwareTrait
{

    public function setAuthService(\Zend\Authentication\AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    public function getAuthService()
    {
        return $this->authService;
    }

    /** @var  \Application\Controller\Plugin\Identity */
    protected $identity;

    public function setIdentityPlugin(\Application\Controller\Plugin\Identity $identityPlugin)
    {
        $this->identity = $identityPlugin;
        return $this;
    }

    protected function getIdentityPlugin()
    {
        return $this->identity;
    }
}
