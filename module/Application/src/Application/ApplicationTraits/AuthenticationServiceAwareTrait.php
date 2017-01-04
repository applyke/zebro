<?php

namespace Application\ApplicationTraits;

trait AuthenticationServiceAwareTrait
{
    /** @var  \Doctrine\Orm\EntityManager */
    protected $authentication_service;

    public function getAuthenticationService()
    {
        return $this->authentication_service;
    }

    public function setAuthenticationService(\Zend\Authentication\AuthenticationService $em)
    {
        $this->authentication_service = $em;
        return $this;
    }
}
