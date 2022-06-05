<?php

declare(strict_types=1);

class RegisterUserToNewsLetterListener implements ListenerInterface
{
    public function handle(EventsInterface $event) : iterable
    {
        echo 'RegisterTo newLetter' . PHP_EOL;
        return ['RegisterTo newLetter'];
    }
}