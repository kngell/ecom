<?php

declare(strict_types=1);

class UserListener implements ListenerInterface
{
    /**
     * @param EventsInterface $event
     */
    public function handle(EventsInterface $event): iterable
    {
        $object = $event->getObject();

        if ($object instanceof Entity) {
            // do something
        }
        return [];
    }
}