<?php

declare(strict_types=1);

interface EmailSenderEventInterface
{
    public function getEmailConfig() : EmailSenderConfiguration;
}