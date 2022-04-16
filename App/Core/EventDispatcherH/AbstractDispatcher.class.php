<?php

declare(strict_types=1);

abstract class AbstractDispatcher implements DispatcherInterface
{
    protected function listnerCanBeInstantiated(string $class) : ReflectionClass
    {
        $reflector = new ReflectionClass($class);
        if (!$reflector->isInstantiable()) {
            throw new BaseInvalidArgumentException("Listener can not be instantiate [$class]!");
        }
        return $reflector;
    }

    protected function checkEvent(string $name) : void
    {
        if (!$this->exists($name)) {
            throw new BaseInvalidArgumentException("No event has been registered under [$name] , please check your config!");
        }
    }

    protected function listnerCanBeAdded(string $listener) : void
    {
        $reflector = new ReflectionClass($listener);
        if (!$reflector->implementsInterface(ListenerInterface::class)) {
            throw new BaseInvalidArgumentException("Listener must implement the listener interface, passed in Listenier [$listener]");
        }
    }
}