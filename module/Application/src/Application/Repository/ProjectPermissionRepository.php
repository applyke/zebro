<?php

namespace Application\Repository;

use Application\Entity\Project;

class ProjectPermissionRepository extends Repository
{
    public function getUsersInProject(Project $project)
    {
        $all_permission_by_project = $this->findBy(array('project' => $project ));
        $users = array();
        foreach ($all_permission_by_project as $value){
            $user[] = $value->getUser();
        }
        return $users;
    }
}
