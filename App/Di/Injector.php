<?php


namespace App\Di;


use App\Controller\Exception\MethodDoesNotExistException;

class Injector
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class_name
     *
     * @return object
     * @throws \ReflectionException
     */
    public function createClassInstance(string $class_name, array $simple_params = []): object
    {
        $reflection_class = new \ReflectionClass($class_name);
        $reflection_constructor = $reflection_class->getConstructor();
        $arguments = [];
        if (!is_null($reflection_constructor)) {
            $arguments = $this->getDependencies($reflection_constructor, $simple_params);
        }
        return $reflection_class->newInstanceArgs($arguments);
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return mixed
     * @throws MethodDoesNotExistException
     * @throws \ReflectionException
     */
    public function callMethod(object $object, string $method)
    {
        $reflection_object = new \ReflectionObject($object);
        if (!$reflection_object->hasMethod($method)) {
            throw new MethodDoesNotExistException($object, $method);
        }
        $reflection_method = $reflection_object->getMethod($method);
        $arguments = $this->getDependencies($reflection_method);
        return call_user_func_array([$object, $method], $arguments);
    }

    /**
     * @param \ReflectionMethod $reflection_method
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getDependencies(\ReflectionMethod $reflection_method, array $simple_params = []): array
    {
        $arguments = [];
        $simple_param_position = 0;
        $reflection_parameters = $reflection_method->getParameters();
        foreach ($reflection_parameters as $reflection_parameter) {
            $reflection_parameter_class = $reflection_parameter->getClass();
            if (is_null($reflection_parameter_class)) {
                if (isset($simple_params[$simple_param_position])) {
                    $arguments[] = $simple_params[$simple_param_position];
                } elseif ($reflection_parameter->isDefaultValueAvailable()) {
                    $arguments[] = $reflection_parameter->getDefaultValue();
                } else {
                    $arguments[] = null;
                }
                $simple_param_position++;
                continue;
            }
            $reflection_parameter_classname = $reflection_parameter_class->getName();
            $arguments[] = $this->container->get($reflection_parameter_classname);
        }
        return $arguments;
    }
}