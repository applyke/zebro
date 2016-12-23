<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            '/' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '[:controller][/:action][/:id][/:id2][/]',
                            'constraints' => array(
                                '__NAMESPACE__' => 'Application\Controller',
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'id2' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'index'
                            ),
                        ),
                    ),
                ),
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/[index/[:page]]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
//            'project' => array(
//                'type' => 'Zend\Mvc\Router\Http\Literal',
//                'options' => array(
//                    'route' => '/',
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'Application\Controller',
//                    ),
//                ),
//                'may_terminate' => true,
//                'child_routes' => array(
//                    'default' => array(
//                        'type' => 'Zend\Mvc\Router\Http\Segment',
//                        'options' => array(
//                            'route' => '/[:controller][/:action][/:id][/:id2][/]',
//                            'constraints' => array(
//                                '__NAMESPACE__' => 'Application\Controller',
//                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'id' => '[0-9]+',
//                                'id2' => '[0-9]+',
//                            ),
//                            'defaults' => array(
//                                'action' => 'index'
//                            ),
//                        ),
//                    ),
//                ),
//            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'logger' => 'Application\Factory\Service\LoggerServiceFactory',
            'pagination' => 'Application\Factory\Service\PaginationServiceFactory',


        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Error' => Controller\ErrorController::class,
            
        ),
        'factories' => array(
            'Application\Controller\Index' => Factory\Controller\IndexFactory::class,
            'Application\Controller\Projects' => Factory\Controller\ProjectsFactory::class,
            'Application\Controller\Issues' => Factory\Controller\IssuesFactory::class,
            'Application\Controller\Boards' => Factory\Controller\BoardsFactory::class,
            )
    ),
    'view_helpers' => array(
        'invokables' => array(),
        'factories' => array(
            'utils' => '\Application\View\Helper\UtilsHelperFactory',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities',
                ),
            )
        ),
        'fixture' => array()
    ),
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'layout/404' => __DIR__ . '/../view/error/404.phtml',
            'layout/error' => __DIR__ . '/../view/error/error.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ],
);
