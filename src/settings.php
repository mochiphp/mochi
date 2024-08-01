<?php

define('APP_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => false,
        'error' => [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true,
        ],
        'logger' => [
            'path' => APP_ROOT . '/logs',
            'level' => \Monolog\Level::Debug,
        ],
        'sessions' => [
            'name' => 'app',
            'lifetime' => 7200,
            'path' => null,
            'domain' => null,
            'secure' => false,
            'httponly' => true,
            'cache_limiter' => 'nocache',
        ]
    ],
];
