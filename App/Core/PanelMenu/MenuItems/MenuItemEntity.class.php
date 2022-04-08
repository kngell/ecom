<?php

declare(strict_types=1);

class MenuItemEntity extends Entity implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            //BeforeRenderActionEvent::NAME => []
        ];
    }
}