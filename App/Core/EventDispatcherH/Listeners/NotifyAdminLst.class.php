<?php

declare(strict_types=1);

class NotifyAdminLst implements ListenerInterface
{
    public function handle(EventsInterface $event) : string
    {
        echo 'Slack Message here' . PHP_EOL;
        return 'Slack Message here';
    }
}