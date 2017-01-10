<?php

namespace Application\Repository;

use Application\Entity\Company;

class ProjectRepository extends Repository
{
    public function getProjectsInCompany(Company $company)
    {
        return $this->findBy(array('company'=>$company));
    }
}
