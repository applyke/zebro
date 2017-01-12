<?php

namespace Application\Repository;

use Application\Entity\Company;

class UserRepository extends Repository
{
    public function getUsersInCompany( Company $company, $array_disable_users_in_result = array())
    {

       return $this->findBy(array('companies' => $company));//TODO: Error in this line. But  I don't why.
    }
}

