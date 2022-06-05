<?php

declare(strict_types=1);
class CheckMultipleVotesListener implements ListenerInterface
{
    public function __construct()
    {
    }

    public function handle(EventsInterface $event): iterable
    {
        return [$event->getName()];
    }
}