<?php

declare(strict_types=1);

class WelcomeVisitorListener
{
    public function getListenersForEvent(EventsInterface $event) : iterable
    {
        echo 'WelcomeNewCustomer' . PHP_EOL;
        return ['WelcomeNewCustomer'];
    }
}