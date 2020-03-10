<?php


namespace App\Router;


use App\Controller\AbstractController;
use App\Controller\Exception\MethodDoesNotExistException;

class Route
{
    /**
     * @var AbstractController
     */
    private $controller;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $params;

    /**
     * Route constructor.
     * @param AbstractController $controller
     * @param string $method
     * @param array $params
     * @throws MethodDoesNotExistException
     */
    public function __construct(AbstractController $controller, string $method, array $params = [])
    {
        $this->isMethodExist($controller, $method);
        $this->controller = $controller;
        $this->method = $method;
        $this->params = $params;
        $this->controller->setRoute($this);
    }

    /**
     * @return AbstractController
     */
    public function getController(): AbstractController
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getParam(string $key)
    {
        return $this->params[$key] ?? null;
    }

    /**
     * @param AbstractController $controller
     * @param string $method
     *
     * @return bool
     * @throws MethodDoesNotExistException
     */
    private function isMethodExist(AbstractController $controller, string $method): bool
    {
        $reflection_object = new \ReflectionObject($controller);
        if (!$reflection_object->hasMethod($method)) {
            throw new MethodDoesNotExistException($controller, $method);
        }
        return true;
    }
}