<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('Configuration');
        if (!empty($config['php_settings'])) {
            foreach ($config['php_settings'] as $key => $value) {
                ini_set($key, $value);
            }
        }
        $serviceManager = $e->getApplication()->getServiceManager();

        $serviceManager->get('logger');
        \Zend\Log\Logger::registerErrorHandler($serviceManager->get('logger'));
        \Zend\Log\Logger::registerExceptionHandler($serviceManager->get('logger'));

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $request = $e->getRequest();
        if (!$request instanceof \Zend\Console\Request) {
            $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -1);    // -1 - very low priority - system callback will be run first
            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), -1);
            $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), -1);
            $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), -1);
        }
    }

    public function onRoute(MvcEvent $e)
    {
        $controllerName = $e->getRouteMatch()->getParam('controller');
        $actionName = $e->getRouteMatch()->getParam('action');

        $filter = new \Zend\Filter\Word\UnderscoreToCamelCase();
        $controllerName = $filter->filter($controllerName);
        $actionName = $filter->filter($actionName);

        if (!method_exists($controllerName . 'Controller', $actionName . 'Action')) {
            $e->getApplication()->getResponse()->setStatusCode(404);
            return $e->getApplication()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
        }
    }

    public function onRenderError(MvcEvent $e)
    {
        return $this->handleErrors($e);
    }

    public function onDispatchError(MvcEvent $e)
    {
        $request = $e->getRequest();
        if (!$request instanceof \Zend\Console\Request) {
            $statusCode = $e->getApplication()->getResponse()->getStatusCode();
            if ($statusCode == 404) {
                if (strpos($request->getUri(), '/api/') === false) {
                    $e->getViewModel()->setTemplate('layout/404');
                    $routeMatch = $e->getRouteMatch();

                    if (is_object($routeMatch)) {
                        // render 404 error page
                        return $routeMatch
                            ->setParam('controller', 'Error')
                            ->setParam('action', 'error404')
                            ->setParam('__NAMESPACE__', 'Application\Controller');
                    }
                }
            } else {
                return $this->handleErrors($e);
            }
        }
    }

    public function onFinish(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager();
    }

    private function handleErrors(MvcEvent $e, $explain = '')
    {
        $errorHandler = $e->getApplication()->getServiceManager()->get('logger');
        $exception = $e->getParam('exception');
        $uri = $e->getRequest()->getRequestUri();
        $msg = '';
        $msg .= 'Error while request URI: ' . $uri . PHP_EOL;
        if ($explain) {
            $msg .= 'Reason: ' . $explain . PHP_EOL;
        }
        if (is_object($exception)) {
            $trace = $exception->getTraceAsString();
            $i = 1;
            do {
                $messages[] = $i++ . ": " . $exception->getMessage();
            } while ($exception = $exception->getPrevious());

            $msg .= "Exception:" . PHP_EOL . implode(PHP_EOL, $messages);
            $msg .= PHP_EOL . "Trace:" . PHP_EOL . $trace . PHP_EOL;
        }

        $errorHandler->err($msg);

        if (strpos($e->getRequest()->getUri(), '/albums/') === false) {

            $e->getViewModel()->setTemplate('layout/error');

            $routeMatch = $e->getRouteMatch();
            if (is_object($routeMatch)) {
                // render 404 error page
                return $e->getRouteMatch()
                    ->setParam('controller', 'Error')
                    ->setParam('action', 'error')
                    ->setParam('__NAMESPACE__', 'Application\Controller');
            }
        }
    }
}
