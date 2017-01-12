<?php

namespace Application\Repository;

use Application\Entity\Project;

class StatusRepository extends Repository
{
    public function saveStatusesForProject(Project $project, array $statuses)
    {
        $entityManager = $this->getEntityManager();
        foreach ($statuses as $status){
            $issue_type = new \Application\Entity\Status();
            $issue_type->setProject($project);
            $issue_type->setTitle($status->getTitle());
            $issue_type->setCode($status->getCode());
            $entityManager->persist($issue_type);
        }
        $entityManager->flush();
    }
}
