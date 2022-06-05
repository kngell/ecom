<?php

declare(strict_types=1);

class LoginEvent extends Event
{
    /**
     * Construct.
     *
     * @param object $object
     * @param string|null $eventName
     */
    public function __construct(object $object, ?string $eventName = null)
    {
        parent::__construct($object, $eventName == null ? $this::class : $eventName);
    }
}