<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;


class Rbac extends AbstractPlugin
{
    const DEFAULT_ROLE = 'anonymous';
    const EVENT_LOGIN = 'user.login';
    const EVENT_PERMISSION_DENIED = 'user.permission.denied';

    /**
     * @var \Zend\Permissions\Acl\Acl
     */
    private $acl;

    /**
     * @var \Zend\Mvc\MvcEvent
     */
    private $mvcEvent;

    private $resourceMap = array();

    private $config = array();

    public function checkACL(\Zend\Mvc\MvcEvent $MvcEvent)
    {
        $this->mvcEvent = $MvcEvent;
        $Config = $MvcEvent->getApplication()->getServiceManager()->get('Config');
        $this->config = $Config['rbac'];

        $acl = new Acl();

        $acl->deny();

        $resource = $MvcEvent->getRouteMatch()->getParam('controller');
        $resource = strtolower($resource);
        $resource = str_replace('\\', ':', $resource);
        $actionName = strtolower($MvcEvent->getRouteMatch()->getParam('action'));
        $requestedResource = $resource . ':' . $actionName;

        $userRole = $this->getUserRole(array_keys($this->config));

        define('USER_ROLE', $userRole);
        define('IS_LOGGED_IN', ($userRole !== self::DEFAULT_ROLE));

        foreach ($this->config as $role => $data) {
            $acl->addRole(new Role($role), $data['extends']);
            if (!empty($data['allow_all'])) {
                $acl->allow($role); // allow all privileges
            } else {
                foreach ($data['allow'] as $moduleN => $resourceArr) {
                    foreach ($resourceArr as $allowedResource) {
                        $fullAllowedResource = $moduleN . ':' . $allowedResource;
                        $this->resourceMap[$fullAllowedResource] = $fullAllowedResource;
                        $acl->addResource($fullAllowedResource);
                        $acl->allow($role, $fullAllowedResource);
                    }
                }
            }
        }

        $this->acl = $acl;

        if (!$this->checkAccess($userRole, $requestedResource)) {
            if ($userRole === self::DEFAULT_ROLE) {
                // show login window
                return $MvcEvent->getApplication()->getEventManager()->trigger(self::EVENT_LOGIN, $MvcEvent);
            }

            // user does not have access to the controller method
            $MvcEvent->getApplication()->getResponse()->setStatusCode(401);
            return $MvcEvent->getApplication()->getEventManager()->trigger(self::EVENT_PERMISSION_DENIED, $MvcEvent);
        }
    }

    public function getUserRole($existing_roles = array())
    {
        $role = self::DEFAULT_ROLE;
        if (empty($_COOKIE[session_name()])) {
            return $role;
        }
        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService = $this->mvcEvent->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
        $identity = $authService->getIdentity();
        if (is_int($identity)) {
            $userRepository = $this->mvcEvent->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager')->getRepository('Application\Entity\User');
            $user = $userRepository->findOneById($identity);
            if (!$user) {
                // user removed from db
                return $role; // return default role
            }
            $session_role = $user->getRole()->getCode();
            // check if session role exists in config
            if (in_array($session_role, $existing_roles)) {
                $role = $session_role;
            }
        }

        return $role;
    }

    /**
     * @param string $role
     * @param string $requestedResource module:controller:action
     * @param null $privilege
     * @return bool
     */
    public function checkAccess($role = '', $requestedResource = '', $privilege = null)
    {
        if (!strlen($requestedResource)) {
            return false;
        }

        $resourceArr = explode(':', $requestedResource);
        if (!count($resourceArr)) {
            return false;
        }

        // check if allowed all resources for the role
        if ($this->acl->isAllowed($role)) {
            return true;
        }

        // check access to the whole controller
        $resourceArr[count($resourceArr) - 1] = '';
        $res = implode(':', $resourceArr);
        if ($this->acl->hasResource($res) && $this->acl->isAllowed($role, $res, $privilege)) {
            return true;
        }

        // check access to the specific action
        if ($this->acl->hasResource($requestedResource)) {
            return $this->acl->isAllowed($role, $requestedResource, $privilege);
        }

        return false;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
