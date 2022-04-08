<?php

declare(strict_types=1);
class Dispatcher extends AbstractDispatcher
{
    public function __construct(private array $listeners, private array $log)
    {
    }

    /** @inheritDoc */
    public function dispatch(EventsInterface $event, bool $debug = false) : void
    {
        $this->checkEvent(name: $event::class);
        foreach ($this->getListenersForEvent(name: $event::class) as $listener) {
            $this->listnerCanBeInstantiated(class: $listener);
            /**
             * @var mixed
             */
            $result = Container::getInstance()->make($listener)->handle(event: $event);
            if ($debug) {
                $this->log[$event::class][] = $result;
            }
        }
    }

    /** @inheritDoc */
    public static function make(array $listeners = []) : static
    {
        return new static(listeners:$listeners, log:[]);
    }

    /** @inheritDoc */
    public function add(string $name, array $listeners) : void
    {
        foreach ($listeners as $listener) {
            $this->listnerCanBeAdded(listener: $listener);
        }
        $this->listeners[$name] = $listeners;
    }

    /** @inheritDoc */
    public function append(string $name, array $listeners) : void
    {
        $this->checkEvent(name:$name);
        foreach ($listeners as $listener) {
            array_push($this->listeners[$name], $listener);
        }
    }

    /** @inheritDoc */
    public function remove(string $name, string $listener) : void
    {
        $this->checkEvent(name:$name);
        if (!$this->hasListener(event: $name, listener: $listener)) {
            throw new BaseInvalidArgumentException("Listener has not been registered for [$name]", 1);
        }
        foreach ($this->getListenersForEvent(name: $name) as $key => $item) {
            if ($item == $listener) {
                unset($this->listeners[$name][$key]);
            }
        }
    }

    /** @inheritDoc */
    public function removeAll(string $name) :void
    {
        $this->checkEvent(name:$name);
        unset($this->listeners[$name]);
    }

    /** @inheritDoc */
    public function hasListener(string $event, string $listener) : bool
    {
        return isset($this->listeners[$event]) ? in_array($listener, $this->listeners[$event]) : false;
    }

    /** @inheritDoc */
    public function exists(string $name) : bool
    {
        return array_key_exists($name, $this->listeners);
    }

    /** @inheritDoc */
    public function getListenersForEvent(string $name) : array
    {
        $this->checkEvent(name:$name);
        return $this->listeners[$name];
    }

    /** @inheritDoc */
    public function listeners() : array
    {
        return $this->listeners;
    }

    /** @inheritDoc */
    public function log() : array
    {
        return $this->log;
    }
}