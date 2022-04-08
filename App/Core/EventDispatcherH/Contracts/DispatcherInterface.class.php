<?php

declare(strict_types=1);
interface DispatcherInterface
{
    /**
     * Dispatch an event with its registered listeners
     * -------------------------------------------------------------.
     * @param EventsInterface $event
     * @param bool $debug
     * @return void
     */
    public function dispatch(EventsInterface $event, bool $debug = false) : void;

    /**
     * Make e new Dispatcher
     * ---------------------------------------------------------------.
     * @param array $listenerss
     * @return static
     */
    public static function make(array $listeners = []) : static;

    /**
     * Add an event with its Dispatcher
     * ---------------------------------------------------------------.
     * @param string $name
     * @param array $listeners
     * @return void
     */
    public function add(string $name, array $listeners) : void;

    /**
     * Append a Series of listeners into the events listeners array.
     * --------------------------------------------------------------.
     * @param string $name
     * @param array $listenerss
     * @return void
     */
    public function append(string $name, array $listenerss) : void;

    /**
     * Remove a specific listeners from the events array for an event.
     * --------------------------------------------------------------.
     * @param string $name
     * @param string $listeners
     * @return void
     */
    public function remove(string $name, string $listener) : void;

    /**
     * Remove all listeners and the event for the passed Event.
     * -------------------------------------------------------------.
     * @param string $name
     * @return void
     */
    public function removeAll(string $name) :void;

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
     * Return All listenerss registered against a passed in event.
     * ------------------------------------------------------------.
     * @param string $name
     * @return array
     */
    public function getListenersForEvent(string $name) : array;

    /**
     * Return All listenerss registered in a dispatcher.
     * -------------------------------------------------------------.
     * @return array
     */
    public function listeners() : array;

    /**
     * Return the Debug log
     * -------------------------------------------------------------.
     * @return array
     */
    public function log() : array;
}