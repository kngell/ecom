<?php

declare(strict_types=1);

abstract class AbstractEventDispatcher implements EventDispatcherInterface
{
    protected function getEvent(string|EventsInterface $event, ?object $obj = null, array $args = []) : EventsInterface
    {
        if (is_string($event)) {
            /** @var Event */
            $eventObj = Container::getInstance()->make($event, ['object' => $obj]);
            $eventObj->setName($eventObj::class);
            $eventObj->setParams($args);
            if (!$eventObj instanceof EventsInterface) {
                throw new BadEnventDispatcherException("[$eventObj::class] is not a valid Event Object!", 1);
            }
            return $eventObj;
        }
        return $event;
    }
}