<?php
declare(strict_types=1);

namespace Atar\Web;

use Atar\Web\Exception\HttpMethodNotAllowedException;
use Atar\Web\Exception\HttpNotFoundException;
use FastRoute\Dispatcher as FastRouteDispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

class Dispatcher
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var RouteCollection
     */
    private $routeCollection;
    /**
     * @var \FastRoute\Dispatcher
     */
    private $dispatcher;

    /**
     * Dispatcher constructor.
     * @param ContainerInterface $container
     * @param RouteCollection $routeCollection
     */
    public function __construct(
        ContainerInterface $container,
        RouteCollection $routeCollection
    )
    {
        $this->container = $container;
        $this->routeCollection = $routeCollection;
        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routeCollection->getRoutes() as list($method, $route, $handler)) {
                $r->addRoute($method, $route, $handler);
            }
        });

    }

    public function dispatch(RequestInterface $request)
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case FastRouteDispatcher::NOT_FOUND:
                throw new HttpNotFoundException("Page not found!", 404);
            case FastRouteDispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1]; // TODO: add allowed method to exception
                throw new HttpMethodNotAllowedException(
                    "Method {$request->getMethod()} is not allowed for given address."
                );
            case FastRouteDispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                return [
                    $handler,
                    new VariableCollection($vars)
                ];
        }
        throw new \LogicException(
            "Request dispatching is not complete!"
        );
    }

}