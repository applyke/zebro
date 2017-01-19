<?php

namespace Application\Repository;

use Application\Entity\Project;
use Application\Entity\User;
use Application\Entity\Company;

class ProjectPermissionRepository extends Repository
{
    public function getUsersInProject(Project $project)
    {
        $all_permission_by_project = $this->findBy(array('project' => $project ));
        $users = array();
        foreach ($all_permission_by_project as $key => $value){
            $users[] = $value->getUser();
        }
        return $users;
    }

    public function getUsersProjectWithPagination(User $user,array $orderBy = null, $limit = null, $offset = null, array $filter = null )
    {
        $all_permission_by_user = $this->findByWithTotalCount(array('user' => $user ), $orderBy, $limit, $offset, $filter);
        $projects = array();
        foreach ($all_permission_by_user as $value){
            $projects[] = $value->getProject();
        }
        return $projects;
    }
//
//    public function getLeadPermissionToProject(User $user, Project $project )
//    {
//        $permission = $this->findOneBy(array('user'=>$user , 'project'=> $project));
//        if(!$permission){
//            $permission = new \Application\Entity\ProjectPermission();
//            $permission->setUser($user);
//            $permission->setProject($project);
//        }
//        $permission->setCreateTask(1);
//        $permission->setUpdateTask(1);
//        $permission->setCreateProject(1);
//        $permission->setUpdateProject(1);
//        $permission->setInviteToProject(1);
//        $permission->setReadProject(1);
//        $permission->setAddProjectToArchive(1);
//        $permission->setDeleteUserFromProject(1);
//        $permission->setChangePermission(1);
//       return $permission;
//    }
//
//    public function getCompaniesProjectWhenUserCanInvite(User $user, Company $company)
//    {
//        $query = $this->createQueryBuilder('perm')
//            ->innerJoin('perm.project', 'project')
//            ->where('perm.user= :user')
//            ->setParameter('user', $user)
//            ->andWhere('perm.invite_to_project = 1')
//            ->andWhere('project.company = :company')
//            ->setParameter('company', $company);
//
//
//        return $query->getQuery()->getResult();
//    }
}
