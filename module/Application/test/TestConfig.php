<?php
namespace ApplicationTest;

$applicationEnv = getenv('APP_ENV') ?: 'production';

return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineDataFixtureModule',
        'DoctrineORMModule',
        'Application',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            sprintf('config/autoload/{,*.}{global,%s,local}.php', $applicationEnv)
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
);