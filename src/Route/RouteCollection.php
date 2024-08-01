<?php

namespace Mochi\Route;

use Psr\Http\Server\RequestHandlerInterface;

class RouteCollection
{
    private array $routes = [];

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function addRoute(string $method, string $path, callable|RequestHandlerInterface $handler, array $args): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'args');
    }
}
