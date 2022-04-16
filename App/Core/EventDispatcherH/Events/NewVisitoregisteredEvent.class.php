<?php

declare(strict_types=1);

class NewVisitoregisteredEvent implements EventsInterface
{
    public function getName(): string
    {
        return 'Customer-has-registerd-event';
    }
}