<?php

namespace Application\ApplicationTraits;

trait LoggerAwareTrait
{
    /** @var \Zend\Log\LoggerInterface */
    protected $logger;

    public function setLogger(\Zend\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    protected function getLogger()
    {
        return $this->logger;
    }
}