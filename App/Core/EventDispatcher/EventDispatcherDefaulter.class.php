<?php

declare(strict_types=1);

class EventDispatcherDefaulter
{
    public const DEFAULT_MESSAGES = [
        'new_password' => 'Your request was successful. Please check your email address for reset link',
        'password_reset' => 'Password reset successfully.',
        'new_activation' => 'You\'re now activated',
    ];
}