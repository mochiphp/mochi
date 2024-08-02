<?php

use Mochi\Middleware\ExceptionMiddleware;
use Mochi\Renderer\Renderer;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UploadedFileFactory;
use Slim\Psr7\Factory\UriFactory;
use Smarty;

return [
    // Application settings
    'settings' => fn () => require __DIR__ . '/settings.php',

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Register Routes
        (require $container->get('route_discovery'))($app);

        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },

    // HTTP factories
    ResponseFactoryInterface::class => function () {
        return new ResponseFactory();
    },

    ServerRequestFactoryInterface::class => function () {
        return new ServerRequestFactory();
    },

    StreamFactoryInterface::class => function () {
        return new StreamFactory();
    },

    UploadedFileFactoryInterface::class => function () {
        return new UploadedFileFactory();
    },

    UriFactoryInterface::class => function () {
        return new UriFactory();
    },

    // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    LoggerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];
        $logger = new Logger('app');

        $filename = sprintf('%s/app.log', $settings['path']);
        $level = $settings['level'];
        $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level, true, 0777);
        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));
        $logger->pushHandler($rotatingFileHandler);

        return $logger;
    },

    ExceptionMiddleware::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['error'];

        return new ExceptionMiddleware(
            $container->get(ResponseFactoryInterface::class),
            $container->get(Renderer::class),
            $container->get(LoggerInterface::class),
            (bool)$settings['display_error_details'],
        );
    },

    Smarty::class => function () {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../../../../templates/');
        $smarty->setCompileDir(__DIR__ . '/../../../../templates_c/');
        $smarty->setCacheDir(__DIR__ . '/../../../../cache/');
        $smarty->setConfigDir(__DIR__ . '/../../../../config/');
        return $smarty;
    },

    Renderer::class => function (ContainerInterface $container) {
        return new Renderer($container->get(Smarty::class));
    },

    'route_discovery' => function () {
        $locations = [
            __DIR__ . '/../../../../routes.php',
            __DIR__ . '/../../../../config/routes.php',
            __DIR__ . '/../../../../app/routes.php',
        ];

        foreach ($locations as $location) {
            if (file_exists($location)) {
                return $location;
            }
        }

        throw new RuntimeException('No routes file found in any location.');
    },
];
