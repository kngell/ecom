<?php

declare(strict_types=1);
class ListenerProvider extends AbstractEventListener
{
    public function __construct(private array $listeners = [], private array $log = [])
    {
    }

    /** @inheritDoc */
    public function getListenersForEvent(EventsInterface $event): iterable
    {
        $eventType = get_class($event);
        if (array_key_exists($eventType, $this->listeners)) {
            return $this->listeners[$eventType];
        }
        return [];
    }

    /** @inheritDoc */
    public function add(string $name, callable $callable, int $priority = 0) : self
    {
        foreach ($callable as $listener) {
            $this->listnerCanBeAdded(listener: $listener);
        }
        $this->listeners[$name][] = ['callback' => $callable, 'priority' => $priority];
        return $this;
    }

    /** @inheritDoc */
    public function exists(string $name) : bool
    {
        return array_key_exists($name, $this->listeners);
    }

    /** @inheritDoc */
    public function hasListener(string $event, string $listener) : bool
    {
        return isset($this->listeners[$event]) ? in_array($listener, $this->listeners[$event]) : false;
    }

    /** @inheritDoc */
    public function removeAll(string $name) :void
    {
        $this->checkEvent(name:$name);
        unset($this->listeners[$name]);
    }

    /** @inheritDoc */
    public function remove(EventsInterface $event, string $listener) : void
    {
        $this->checkEvent(name:$event::class);
        if (!$this->hasListener(event: $event::class, listener: $listener)) {
            throw new BaseInvalidArgumentException("Listener has not been registered for [$event::class]", 1);
        }
        foreach ($this->getListenersForEvent(event: $event) as $key => $item) {
            if ($item == $listener) {
                unset($this->listeners[$event::class][$key]);
            }
        }
    }

    public function detach(EventsInterface $event, callable $callback) : void
    {
        $this->listeners[$event->getName()] = array_filter($this->listeners[$event->getName()], function ($listener) use ($callback) {
            return $listener['callback'] !== $callback;
        });
    }

    /**
     * @param string $eventType
     */
    public function clearListeners(string $eventType): void
    {
        if (array_key_exists($eventType, $this->listeners)) {
            unset($this->listeners[$eventType]);
        }
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