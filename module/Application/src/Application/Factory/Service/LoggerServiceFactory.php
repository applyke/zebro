<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Writer as LogWriter;
use Zend\Console\Request as ConsoleRequest;
use Zend\Log\Logger;

class LoggerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dir = realpath(__DIR__ . '/../../../../../../data/log');
        $filename = $dir . '/' . date('Y-M') . '-log.txt';
        $request = $serviceLocator->get('application')->getMvcEvent()->getRequest();
        if (!$request instanceof ConsoleRequest) {
            $writer = new LogWriter\Stream($filename);
        } else {
            $writer = new LogWriter\Noop($filename);
        }
        $logger = new Logger();
        $logger->addWriter($writer);
        return $logger;
    }
}