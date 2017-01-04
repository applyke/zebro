<?php
/**
 * Store only User id in the Storage instead of the serialized User entity (not recommended)
 */

namespace Application\Service\Auth\Adapter;


use Zend\Authentication\Adapter\Exception;
use Zend\Authentication\Result as AuthenticationResult;

class ObjectRepository extends \DoctrineModule\Authentication\Adapter\ObjectRepository
{
    protected function validateIdentity($identity)
    {
        $credentialProperty = $this->options->getCredentialProperty();
        $getter = 'get' . ucfirst($credentialProperty);
        $documentCredential = null;

        if (method_exists($identity, $getter)) {
            $documentCredential = $identity->$getter();
        } elseif (property_exists($identity, $credentialProperty)) {
            $documentCredential = $identity->{$credentialProperty};
        } else {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'Property (%s) in (%s) is not accessible. You should implement %s::%s()',
                    $credentialProperty,
                    get_class($identity),
                    get_class($identity),
                    $getter
                )
            );
        }

        $credentialValue = $this->credential;
        $callable = $this->options->getCredentialCallable();
        if ($callable) {
            $credentialValue = call_user_func($callable, $identity, $credentialValue);
        }

        if (password_verify($credentialValue, $documentCredential)) {
            $this->authenticationResultInfo['code'] = AuthenticationResult::SUCCESS;
            $this->authenticationResultInfo['identity'] = $identity;
            $this->authenticationResultInfo['messages'][] = 'Authentication successful.';
        } else {
            $this->authenticationResultInfo['code'] = AuthenticationResult::FAILURE_CREDENTIAL_INVALID;
            $this->authenticationResultInfo['messages'][] = 'Supplied credential is invalid.';
        }

        return $this->createAuthenticationResult();
    }

    /**
     * @inheritdoc
     */
    protected function createAuthenticationResult()
    {
        if ($this->authenticationResultInfo['identity'] instanceof \Application\Entity\User) {
            $this->authenticationResultInfo['identity'] = $this->authenticationResultInfo['identity']->getId();
        }

        return parent::createAuthenticationResult();
    }
}