<?php

declare(strict_types=1);
interface ListenerProviderInterface
{
    /**
     * Get Listener for event
     * -------------------------------------------------------.
     * @param EventsInterface $event
     * An event for which to return relevant listeners
     * @return iterable
     */
    public function getListenersForEvent(EventsInterface $event) : iterable;

    /**
     * Add an event with its Dispatcher
     * ---------------------------------------------------------------.
     * @param string $name
     * @param array $listeners
     * @return self
     */
    public function add(string $name, callable $callable) : self;

    /**
     * Append a Series of listeners into the events listeners array.
     * --------------------------------------------------------------.
     * @param string $name
     * @param array $listenerss
     * @return void
     */
    public function append(string $name, array $listenerss) : void;

    /**
     * Check if the passed in event name has been registered.
     * ------------------------------------------------------------.
     * @param string $name
     * @return bool
     */
    public function exists(string $name) : bool;

    /**
     * Check if the passed in listner has been registered for the passed in Event
     * ----------------------------------------------------------.
     * @param string $event
     * @param string $listener
     * @return bool
     */
    public function hasListener(string $event, string $listener) : bool;

    /**
     * Remove all listeners and the event for the passed Event.
     * -------------------------------------------------------------.
     * @param string $name
     * @return void
     */
    public function removeAll(string $name) :void;

    /**
     * Remove a specific listeners from the events array for an event.
     * --------------------------------------------------------------.
     * @param EventsInterface $event
     * @param string $listeners
     * @return void
     */
    public function remove(EventsInterface $event, string $listener) : void;

    public function checkEvent(string $name) : void;

    public function listnerCanBeInstantiated(string $class) : ListenerInterface;
}