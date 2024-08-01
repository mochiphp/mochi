<?php

use Mochi\Middleware\ExceptionMiddleware;
use Mochi\Renderer\JsonRenderer;
use Mochi\Renderer\SmartyRenderer;
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
use Mochi\Route\RouteCollection;
use Mochi\Route\RouteBuilder;
use Slim\Psr7\Factory\UriFactory;


return [
    // Application settings
    'settings' => fn () => require __DIR__ . '/settings.php',

    App::class => function (ContainerInterface $container) {
        $app = AppFactory::createFromContainer($container);

        // Access routes from the RouteCollection
        $routes = $container->get(RouteCollection::class)->getRoutes();

        // Register routes using Slim's methods
        foreach ($routes as $route) {
            $app->{$route['method']}($route['path'], $route['handler']);
        }

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
        $level = $settings['level'] ?? Level::Debug; // Ensure a default level is set
        $rotatingFileHandler = new RotatingFileHandler($filename, 0, $level, true, 0777);
        $rotatingFileHandler->setFormatter(new LineFormatter(null, null, false, true));
        $logger->pushHandler($rotatingFileHandler);

        return $logger;
    },

    ExceptionMiddleware::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['error'];

        return new ExceptionMiddleware(
            $container->get(ResponseFactoryInterface::class),
            $container->get(JsonRenderer::class),
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

    SmartyRenderer::class => function (ContainerInterface $container) {
        return new SmartyRenderer($container->get(Smarty::class));
    },

    JsonRenderer::class => function () {
        return new JsonRenderer();
    },


    RouteCollection::class => function () {
        return new RouteCollection();
    },

    RouteBuilder::class => function (ContainerInterface $container) {
        return new RouteBuilder($container->get(RouteCollection::class));
    },

];
