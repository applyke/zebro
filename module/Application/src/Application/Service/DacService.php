<?php

namespace Application\Service;

use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;
use Application\ApplicationTraits\LoggerAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Mvc\Application as MvcApplication;
use Application\ApplicationTraits\IdentityAwareTrait;

class DacService
{
    use IdentityAwareTrait;
    use DoctrineEntityManagerAwareTrait;
    use LoggerAwareTrait;

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


    public function initialize(array $config, MvcApplication $application)
    {
        $this->config = $config['dac'];
        $this->application = $application;
        $routeMatch = $application->getMvcEvent()->getRouteMatch();
        $acl = new Acl();
        $acl->deny();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        $namespace = $routeMatch->getParam('__NAMESPACE__');
        $routeName = $routeMatch->getMatchedRouteName();
        $this->acl = $acl;
    }

    public function checkAccess($user, $permissionCode)
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
//        $project = $projectRepository->findOneById($projectId);
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        if (!$user) {
            return false;
        }

        $permission = $projectPermissionRepository->findOneBy(array('user' => $user, $permissionCode => 1));

        if ($permission) {
            return true;
        }
        return false;
    }
}
