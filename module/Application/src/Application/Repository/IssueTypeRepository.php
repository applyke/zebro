<?php

namespace Application\Repository;

use Application\Entity\Project;

class IssueTypeRepository extends Repository
{
    public function saveIssueTypeForProject(Project $poject, array $issue_types)
    {
        $entityManager = $this->getEntityManager();
        foreach ($issue_types as $type){
            $issue_type = new \Application\Entity\IssueType();
            $issue_type->setProject($poject);
            $issue_type->setTitle($type->getTitle());
            $issue_type->setCode($type->getCode());
            $entityManager->persist($issue_type);
        }
        $entityManager->flush();
    }
}
