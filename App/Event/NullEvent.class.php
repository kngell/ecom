<?php

declare(strict_types=1);

class NullEvent extends Event implements EventsInterface
{
    public function getName(): string
    {
        return 'null-event';
    }
}