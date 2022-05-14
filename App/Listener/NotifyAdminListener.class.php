<?php

declare(strict_types=1);

class NotifyAdminListener implements ListenerInterface
{
    public function handle(EventsInterface $event): iterable
    {
        echo 'Slack Message here' . PHP_EOL;
        return ['Slack Message here'];
    }
}