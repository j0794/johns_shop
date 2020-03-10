<?php


namespace App\Di;


use App\Di\Exceptions\ClassOrInterfaceNotExistException;

class Container
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var array
     */
    private $singletons = [];

    /**
     * @var array
     */
    private $factories = [];

    /**
     * @var array
     */
    private $interfaces_dictionary = [];

    public function __construct(array $interfaces_dictionary = [])
    {
        $this->injector = new Injector($this);
        $this->singletons[self::class] = $this;
        $this->interfaces_dictionary = $interfaces_dictionary;
    }

    /**
     * @param string $key
     *
     * @return object
     * @throws \ReflectionException
     * @throws ClassOrInterfaceNotExistException
     */
    public function get(string $key)
    {
        if (!class_exists($key)) {
            if (interface_exists($key)) {
                $interface_mapping = $this->getInterfaceMapping($key);
                return $this->get($interface_mapping);
            }
            throw new ClassOrInterfaceNotExistException('Class or interface not exist: ' . $key);
        }
        return $this->getClassInstance($key);
    }

    /**
     * @param string $class_name
     *
     * @return object
     * @throws \ReflectionException
     */
    private function getClassInstance(string $class_name)
    {
        if ($this->isSingleton($class_name)) {
            $instance = $this->getSingleton($class_name);
        } else {
            $instance = $this->getInjector()->createClassInstance($class_name);
        }
        return $instance;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    private function getInterfaceMapping(string $key): ?string
    {
        return $this->interfaces_dictionary[$key] ?? null;
    }

    /**
     * @return Injector
     */
    public function getInjector(): Injector
    {
        return $this->injector;
    }

    /**
     * @param string $class_name
     * @param array $simple_params
     * @param callable|null $callback
     */
    public function setSingleton(string $class_name, array $simple_params = [], callable $callback = null): void
    {
        if ($simple_params) {
            $this->factories[$class_name]['simple_params'] = $simple_params;
        }
        if (is_callable($callback)) {
            $this->factories[$class_name]['callback'] = $callback;
        }
        $this->singletons[$class_name] = false;
    }

    /**
     * @param string $class_name
     *
     * @return bool
     */
    private function isSingleton(string $class_name): bool
    {
        return isset($this->singletons[$class_name]);
    }

    /**
     * @param string $class_name
     *
     * @return object
     * @throws \ReflectionException
     */
    private function getSingleton(string $class_name): object
    {
        $instance = $this->singletons[$class_name];
        if ($instance == false) {
            $instance = $this->createSingletonInstance($class_name);
        }
        return $instance;
    }

    /**
     * @param string $class_name
     *
     * @return object
     * @throws \ReflectionException
     */
    private function createSingletonInstance(string $class_name): object
    {
        $simple_params = [];
        if($this->isFactorySimpleParamsExist($class_name)) {
            $simple_params = $this->getFactorySimpleParams($class_name);
        }
        $instance = $this->getInjector()->createClassInstance($class_name, $simple_params);
        if($this->isFactoryCallbackExist($class_name)) {
            $this->getFactoryCallback($class_name)($instance);
        }
        $this->singletons[$class_name] = $instance;
        return $instance;
    }

    /**
     * @param string $class_name
     *
     * @return bool
     */
    private function isFactorySimpleParamsExist(string $class_name): bool
    {
        return isset($this->factories[$class_name]['simple_params']) && is_array($this->factories[$class_name]['simple_params']);
    }

    /**
     * @param string $class_name
     *
     * @return bool
     */
    private function isFactoryCallbackExist(string $class_name): bool
    {
        return isset($this->factories[$class_name]['callback']) && is_callable($this->factories[$class_name]['callback']);
    }

    /**
     * @param string $class_name
     *
     * @return array
     */
    private function getFactory(string $class_name): array
    {
        return $this->factories[$class_name];
    }

    /**
     * @param string $class_name
     *
     * @return array
     */
    private function getFactorySimpleParams(string $class_name): array
    {
        return $this->getFactory($class_name)['simple_params'];
    }

    /**
     * @param string $class_name
     *
     * @return callable
     */
    private function getFactoryCallback(string $class_name): callable
    {
        return $this->getFactory($class_name)['callback'];
    }
}