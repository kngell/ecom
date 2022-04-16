<?php

declare(strict_types=1);

class RegisterVisitorToNewsLetterLst implements ListenerInterface
{
    public function handle(EventsInterface $event) : string
    {
        echo 'RegisterTo newLetter' . PHP_EOL;
        return 'RegisterTo newLetter';
    }
}