<?php

declare(strict_types=1);

class WelcomeVisitorLst implements ListenerInterface
{
    public function handle(EventsInterface $event) : string
    {
        echo 'WelcomeNewCustomer' . PHP_EOL;
        return 'WelcomeNewCustomer';
    }
}