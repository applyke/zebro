<?php

namespace Application\Repository;

use Application\Entity\Project;

class IssuePriorityRepository extends Repository
{
    public function saveIssuePriorityForProject(Project $poject, array $issue_priorities)
    {
        $entityManager = $this->getEntityManager();
        foreach ($issue_priorities as $priority){
            $issue_priopity = new \Application\Entity\IssuePriority();
            $issue_priopity->setProject($poject);
            $issue_priopity->setTitle($priority->getTitle());
            $issue_priopity->setCode($priority->getCode());
            $issue_priopity->setIcon($priority->getIcon());
            $entityManager->persist($issue_priopity);
        }
        $entityManager->flush();
    }
}
