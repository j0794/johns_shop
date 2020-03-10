<?php


namespace App;


use App\Controller\Exception\MethodDoesNotExistException;
use App\Di\Container;
use App\Di\Exceptions\ClassOrInterfaceNotExistException;
use App\Helper\ClassHelper;
use App\Http\Response;
use App\Middleware\MiddlewareInterface;
use App\Router\Route;
use App\Router\Router;
use App\Router\Exception\NotFoundException;
use ReflectionException;

final class Kernel
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ClassHelper
     */
    private $class_helper;

    /**
     * Kernel constructor.
     * @param Container $container
     * @param Router $router
     * @param ClassHelper $class_helper
     */
    public function __construct(Container $container, Router $router, ClassHelper $class_helper)
    {
        $this->container = $container;
        $this->router = $router;
        $this->class_helper = $class_helper;
    }

    /**
     * @throws MethodDoesNotExistException
     * @throws ReflectionException
     * @throws NotFoundException
     * @throws ClassOrInterfaceNotExistException
     */
    public function run()
    {
        $route = $this->router->getRoute();
        $this->runMiddlewares($route);
        /**
         * @var Response $response
         */
        $response = $this->container->getInjector()->callMethod($route->getController(), $route->getMethod());
        $response->send();
    }

    /**
     * @param Route $route
     * @throws ReflectionException
     * @throws ClassOrInterfaceNotExistException
     */
    private function runMiddlewares(Route $route)
    {
        $middlewares = $this->class_helper->findRecursive('App\\Middleware');
        foreach ($middlewares as $middleware_class) {
            $this->runMiddleware($route, $middleware_class);
        }
    }

    /**
     * @param Route $route
     * @param string $middleware_class
     * @throws ReflectionException
     * @throws ClassOrInterfaceNotExistException
     */
    private function runMiddleware(Route $route, string $middleware_class)
    {
        if (!class_exists($middleware_class)) {
            return;
        }
        if (!in_array(MiddlewareInterface::class, class_implements($middleware_class))) {
            return;
        }

        /**
         * @var MiddlewareInterface $middleware
         */
        $middleware = $this->container->get($middleware_class);
        $middleware->run($route);
    }
}