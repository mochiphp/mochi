<?php

namespace Mochi\Route;

use Psr\Http\Server\RequestHandlerInterface;

class RouteBuilder
{
    private RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    public function __invoke(callable $routes): void
    {
        $routes($this);
    }

    public function get(string $path, callable|RequestHandlerInterface $handler, array $args = []): void
    {
        $this->addRoute('GET', $path, $handler, $args);
    }


    private function addRoute(string $method, string $path, callable|RequestHandlerInterface $handler, array $args): void
    {
        $this->routeCollection->addRoute($method, $path, $handler, $args);
    }
}
