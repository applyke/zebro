<?php

namespace Application\ApplicationTraits;

trait DoctrineEntityManagerAwareTrait
{
      /* @var  \Doctrine\Orm\EntityManager */
    protected $entityManager;

    public function setEntityManager(\Doctrine\Orm\EntityManager  $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }
}