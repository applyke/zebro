<?php

namespace Application\ApplicationTraits;

trait PaginationAwareTrait
{
   /** @var  \Application\Service\PaginationService */
    protected $pagination;

    public function setPaginationService(\Application\Service\PaginationService   $paginationService)
    {
        $this->pagination = $paginationService;
        return $this;
    }

    protected function getPaginationService()
    {
        return $this->pagination;
    }
}