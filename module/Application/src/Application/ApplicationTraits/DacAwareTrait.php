<?php

namespace Application\ApplicationTraits;

trait DacAwareTrait
{
    /** @var  \Application\Service\DacService */
    protected $dacService;

    public function setDacService(\Application\Service\DacService $dac)
    {
        $this->dacService = $dac;
        return $this;
    }

    /**
     * @return \Application\Service\DacService
     */
    protected function getDacService()
    {
        return $this->dacService;
    }

}