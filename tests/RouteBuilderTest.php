<?php

use Mochi\Route\RouteBuilder;
use Mochi\Route\RouteCollection;
use Psr\Http\Server\RequestHandlerInterface;
use PHPUnit\Framework\TestCase;

class RouteBuilderTest extends TestCase
{
    private RouteCollection $routeCollection;
    private RouteBuilder $routeBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routeCollection = new RouteCollection();
        $this->routeBuilder = new RouteBuilder($this->routeCollection);
    }

    public function testGetMethodAddsRouteToCollection(): void
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $this->routeBuilder->get('/test-path', $handler);

        $routes = $this->routeCollection->getRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('GET', $routes[0]['method']);
        $this->assertEquals('/test-path', $routes[0]['path']);
        $this->assertSame($handler, $routes[0]['handler']);
        $this->assertEmpty($routes[0]['args']);
    }

    public function testInvokeMethodCallsRoutesCallable(): void
    {
        $this->routeBuilder->__invoke(function (RouteBuilder $builder) {
            $builder->get('/invoked-path', function () {
            });
        });

        $routes = $this->routeCollection->getRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('/invoked-path', $routes[0]['path']);
        $this->assertIsCallable($routes[0]['handler']);
    }
}
