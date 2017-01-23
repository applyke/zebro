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

    public function checkAccess(\Application\Entity\User $user, $projectId, $permissionCode)
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ProjectPermissionRepository $projectPermissionRepository */
        $projectPermissionRepository = $entityManager->getRepository('\Application\Entity\ProjectPermission');
        if (!$user) {
            return false;
        }
        if (!empty($projectId)) {
            /** @var \Application\Repository\ProjectRepository $projectRepository */
            $projectRepository = $entityManager->getRepository('\Application\Entity\Project');
            $project = $projectRepository->findOneById($projectId);
            if (!$project) {
                return false;
            }
            $permission = $projectPermissionRepository->findOneBy(array('user' => $user, 'project' => $project->getId(), $permissionCode => 1, 'disable_user_in_project' => 0));
        } else {
            $permission = $projectPermissionRepository->findOneBy(array('user' => $user, $permissionCode => 1, 'disable_user_in_project' => 0));
        }

        if ($permission) {
            return true;
        }
        return false;
    }
}
