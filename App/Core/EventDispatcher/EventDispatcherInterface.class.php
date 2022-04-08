<?php

declare(strict_types=1);
/**
 * Defines a dispatcher for events.
 */
interface EventDispatcherInterface
{
    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event - The object to process.
     * @param string|null $eventName - the name of the event created
     * @return object - The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event, ?string $eventName = null) : Object;
}