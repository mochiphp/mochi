<?php

use Mochi\Middleware\ExceptionMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(ExceptionMiddleware::class);
    $app->add(new Zeuxisoo\Whoops\Slim\WhoopsMiddleware([
        'enable' => true,
        'editor' => 'vscode',
        'title'  => 'Test',
    ]));
};
