<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'pages' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
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
            'reset_password' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/user/reset-password/[:password]/',
                    'constraints' => array(

                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action' => 'resetpassword',
                    ),
                ),
            ),
            'setting' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/setting',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller\Setting',
                        'controller' => 'Setting',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '[/:controller][/:action][/:id][/:id2][/]',
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
            'identity' => 'Application\Factory\Plugin\IdentityFactory',

        ),
        'invokables' => array(
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
        )
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
            'Application\Controller\BoardsColumns' => Factory\Controller\BoardsColumnsFactory::class,
            'Application\Controller\Setting\Setting' => Factory\Controller\Setting\SettingFactory::class,
            'Application\Controller\Setting\ProjectCategory' => Factory\Controller\Setting\ProjectCategoryFactory::class,
            'Application\Controller\Setting\ProjectType' => Factory\Controller\Setting\ProjectTypeFactory::class,
            'Application\Controller\Setting\Status' => Factory\Controller\Setting\StatusFactory::class,
            'Application\Controller\Setting\IssuesPriority' => Factory\Controller\Setting\IssuesPriorityFactory::class,
            'Application\Controller\Setting\IssuesType' => Factory\Controller\Setting\IssuesTypeFactory::class,
            'Application\Controller\Company' => Factory\Controller\CompanyFactory::class,
            'Application\Controller\User' => Factory\Controller\UserFactory::class,

        )
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'Rbac' => 'Application\Controller\Plugin\Rbac',
        ),
        'factories' => array(
            'identity' => 'Application\Factory\Plugin\IdentityFactory',
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
