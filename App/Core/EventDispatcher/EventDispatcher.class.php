<?php

declare(strict_types=1);
class EventDispatcher extends AbstractEventDispatcher implements EventDispatcherInterface
{
    public function __construct(private ListenerProviderInterface $listener)
    {
    }

    public function dispatch(string|EventsInterface $event, ?object $obj = null, array $args = [], bool $debug = false) : mixed
    {
        $eventResults = new stdClass();
        $event = $this->getEvent($event, $obj, $args); // contient des détails sur l'évenement
        $this->listener->checkEvent(name: $event->getName());
        $listeners = $this->getListenersForEvent(event: $event);
        foreach ($listeners as ['callback' => $listener]) {
            /** @var ListenerInterface */
            $obj = $this->listener->listnerCanBeInstantiated(class: $listener);
            /*
             * @var mixed
             */
            $eventResults->result = $obj->handle(event: $event);
            $eventResults->listener = $listener;
            if ($debug) {
                $this->log[$event->getName()][] = $eventResults;
            }
        }
        $event->setResults($eventResults);
        return $event;
    }

    public function add(string $name, callable $callable): self
    {
        $this->listener->add($name, $callable);
        return $this;
    }

    public function getListener() : ListenerProviderInterface
    {
        return $this->listener;
    }

    public function getListenersForEvent(EventsInterface $event) : iterable
    {
        /** @var array */
        $listeners = $this->listener->getListenersForEvent(event: $event);
        uasort($listeners, function ($listenerA, $listenerB) {
            return $listenerB['priority'] - $listenerA['priority'];
        });
        return $listeners;
    }
}