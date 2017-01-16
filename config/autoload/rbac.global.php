<?php

return array(
    'rbac' => array(

        /**
         * 'anonymous' role should always be present
         *
         * Should be present entry "module => [namespace:controller:action]" to allow access to the controller-action in any namespace
         *
         * List of possible privileges: read, write
         */

        'anonymous' => array(
            'extends' => array(),
            'allow' => array(
                'application' => array(
                    'controller:index:index',
                    'controller:index:signup',
                    'controller:user:activate',
                    'controller:user:resetpassword',
                )
            ),
        ),
        'user'=>array(
            'extends' => array(),
            'allow_all' => true,
        ),
        'admin' => array(
            'extends' => array(),
            'allow_all' => true,
        )
    )
);
