<?php

declare(strict_types=1);

/**
 * Event is the base class for classes containing event data. This class contains no event data.
 * It is used by events that do not pass state information to an event handler when
 * an event is raised.
 */
class Event implements StoppableEventInterface
{
    /** @var bool */
    private bool $propagationStopped = false;

    /**
     * @inheritdoc
     * @return bool
     */
    public function isPropagationStopped() : bool
    {
        return $this->propagationStopped;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     *
     * @return void
     */
    public function stopPropgation() : void
    {
        $this->propagationStopped = true;
    }
}