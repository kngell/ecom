<?php

declare(strict_types=1);
interface EventDispatcherInterface
{
    /**
     * Dispatch an event with its registered listeners
     * --------------------------------------------------------------.
     * @param string|EventsInterface $event
     * @param object $obj
     * @param array $args
     * @param bool $debug
     * @return mixed
     */
    public function dispatch(string|EventsInterface $event, ?object $obj = null, array $args = [], bool $debug = false) : mixed;
}