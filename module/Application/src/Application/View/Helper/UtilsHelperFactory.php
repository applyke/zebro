<?php

namespace Application\View\Helper;

class UtilsHelperFactory implements \Zend\ServiceManager\FactoryInterface
{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $utils = new UtilsHelper();
        $utils->setPluginManager($serviceLocator);
        return $utils;
    }
}