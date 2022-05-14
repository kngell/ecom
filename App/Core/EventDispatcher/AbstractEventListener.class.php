<?php

declare(strict_types=1);

abstract class AbstractEventListener implements ListenerProviderInterface
{
    public function checkEvent(string $name) : void
    {
        if (!$this->exists($name)) {
            throw new BaseInvalidArgumentException("No event has been registered under [$name] , please check your config!");
        }
    }

    public function listnerCanBeInstantiated(string $class) : ListenerInterface
    {
        $object = Container::getInstance()->make($class);
        if (!$object instanceof ListenerInterface) {
            throw new BaseInvalidArgumentException("Listener can not be instantiate [$class]!");
        }
        return $object;
    }

    protected function listnerCanBeAdded(string $listener) : void
    {
        $reflector = new ReflectionClass($listener);
        if (!$reflector->implementsInterface(ListenerInterface::class)) {
            throw new BaseInvalidArgumentException("Listener must implement the listener interface, passed in Listenier [$listener]");
        }
    }
}