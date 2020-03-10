<?php


namespace App\Router;


use App\Controller\AbstractController;
use App\Controller\Exception\MethodDoesNotExistException;
use App\Di\Container;
use App\Helper\ClassHelper;
use App\Http\Request;
use App\Router\Exception\NotFoundException;

class Router
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ClassHelper
     */
    private $class_helper;

    public function __construct(Container $container, Request $request, ClassHelper $class_helper)
    {
        $this->container = $container;
        $this->request = $request;
        $this->class_helper = $class_helper;
    }

    /**
     * @return Route|null
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function getRoute(): ?Route
    {
        $route = null;
        $route_data = $this->getRouteData();
        if (is_null($route_data)) {
            $this->notFound();
        }
        /**
         * @var AbstractController $controller
         */
        $controller = $this->container->get($route_data['callable'][0]);
        $method = $route_data['callable'][1];
        $params = $route_data['params'] ?? [];
        try {
            $route = new Route($controller, $method, $params);
        } catch (MethodDoesNotExistException $exception) {
            $this->notFound();
        }
        return $route;
    }

    /**
     * @return array|null
     * @throws \ReflectionException
     */
    private function getRouteData(): ?array
    {
        $routes = $this->getRoutes();
        $url = $this->request->getUrl();
        $route_data = $routes[$url] ?? null;
        if (!is_null($route_data)) {
            return $route_data;
        }
        foreach ($routes as $route_url => $route_settings) {
            $url_parts = array_values(array_filter(explode('/', $url)));
            $route_url_parts = array_values(array_filter(explode('/', $route_url)));
            if (count($url_parts) != count($route_url_parts)) {
                continue;
            }
            for ($i = 0; $i < count($url_parts); $i++) {
                $url_part = $url_parts[$i];
                $route_url_part = $route_url_parts[$i];
                $param_by_route_pattern = $this->getParamByRoutePattern($url_part, $route_url_part);
                if (!$param_by_route_pattern && $url_part != $route_url_part) {
                    continue 2;
                }
                $route_settings['params'] = $route_settings['params'] ?? [];
                $route_settings['params'] = array_replace($route_settings['params'], $param_by_route_pattern);
            }
            $route_data = $route_settings;
            break;
        }
        return $route_data;
    }

    /**
     * @param string $url_part
     * @param string $route_url_part
     *
     * @return array
     */
    private function getParamByRoutePattern(string $url_part, string $route_url_part): array
    {
        $param = [];
        if (preg_match('/^{(.+)}$/i', $route_url_part, $matches)) {
            $param_key = $matches[1];
            $param = [
                $param_key => $url_part,
            ];
        }
        return $param;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getRoutes(): array
    {
        $controllers = $this->class_helper->findRecursive('App\\Controller');
        $routes = [];
        foreach ($controllers as $controller) {
            $reflection_controller = new \ReflectionClass($controller);
            $reflection_methods = $reflection_controller->getMethods();
            foreach ($reflection_methods as $reflection_method) {
                $reflection_method_doc_comment =  $reflection_method->getDocComment();
                preg_match('/@Route\((.+)\)/i', $reflection_method_doc_comment, $route_matches);
                if ($route_matches == false) {
                    continue;
                }
                $route_string_parts = explode(',', $route_matches[1]);
                $route_string_parts = array_map(function ($item) {
                    return trim($item);
                }, $route_string_parts);
                $route_url = '';
                foreach ($route_string_parts as $route_string_part) {
                    preg_match('/(?P<key>.+)=(?P<value>.+)/i', $route_string_part, $route_string_param);
                    $param_key = $route_string_param['key'];
                    $param_value = str_replace('\'', '', $route_string_param['value']);
                    if ($param_key == 'url') {
                        $route_url = $param_value;
                        if ($route_url) {
                            $routes[$route_url]['callable'] = [
                                $controller,
                                $reflection_method->getName(),
                            ];
                        }
                    } else {
                        if ($route_url) {
                            $routes[$route_url]['params'][$param_key] = $param_value;
                        }
                    }
                }
            }
        }
        return (array) $routes;
    }

    /**
     * @throws NotFoundException
     */
    private function notFound() {
        echo '404';
        throw new NotFoundException('Page not found');
    }
}