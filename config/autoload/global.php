<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
$rememberMe = 60 * 60 * 8;
return array(
    'session' => array(
        //'phpSaveHandler' => 'redis',
        //'savePath' => 'tcp://127.0.0.1:6379?weight=1&timeout=1',
        'phpSaveHandler' => 'files',
        'savePath' => __DIR__ . '/../../data/session',
        'remember_me_seconds' => $rememberMe,
        'cookieLifetime' => $rememberMe,
        'gc_maxlifetime' => $rememberMe,
        'gc_probability' => 1,
        'gc_divisor' => 10, // every gc_probability/gc_divisor request will start session garbage collector
    ),
    'php_settings' => array(
        'date.timezone' => 'Europe/Kiev',
        'memory_limit' => '128M',
        'display_errors' => 0,
        'error_reporting' => E_ALL,
        'log_errors' => 1,
        'error_log' => __DIR__ . '/../../data/log/' . date('Y-M') . '-fatal.txt',
        'default_charset' => 'UTF-8',
    ),
);
