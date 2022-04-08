<?php

declare(strict_types=1);

class UserProfileActionEvent extends ActionEvent
{
    /** @var string - name of the event */
    public const NAME = 'app.event.userprofile_action_event';
}