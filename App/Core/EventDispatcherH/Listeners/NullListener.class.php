<?php

declare(strict_types=1);
class NullListener implements ListenerInterface
{
    public function handle(EventsInterface $event): mixed
    {
        return $event->getName();
    }
}