<?php

declare(strict_types=1);

interface ListenerInterface
{
    public function handle(EventsInterface $event) : ?iterable;
}