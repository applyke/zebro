<?php

namespace Application\Repository;

use Application\Entity\Company;

class UserRepository extends Repository
{
    public function getUsersInCompany( Company $company, $users_disable_array = array())
    {
       $query = $this->createQueryBuilder('u')
            ->innerJoin('u.companies', 'c')
            ->where('c.id= :companyId')
            ->setParameter('companyId', $company->getId());
        if($users_disable_array){
            $users_id = array();
            foreach ($users_disable_array as $key => $user){
               $users_id[]  = $user->getId();
            }
            $query = $query->andWhere('u.id NOT IN ( :usersId )')
                ->setParameter('usersId', $users_id );
        }
        return $query->getQuery()->getResult();
    }
}

