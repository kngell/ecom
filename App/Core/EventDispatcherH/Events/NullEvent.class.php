<?php

declare(strict_types=1);

class NullEvent implements EventsInterface
{
    public function getName(): string
    {
        return 'null-event';
    }
}