<?php

declare(strict_types=1);

class NewCommentVoteEvent extends Event implements EventsInterface
{
    public function getName(): string
    {
        return 'null-event';
    }
}