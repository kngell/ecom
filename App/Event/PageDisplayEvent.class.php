<?php

declare(strict_types=1);

class PageDisplayingEvent extends Event implements EventsInterface
{
    public function getName(): string
    {
        return $this::class;
    }
}