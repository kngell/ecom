<?php

declare(strict_types=1);

class ForgotPasswordEvent extends Event implements EmailSenderEventInterface
{
    public function __construct(object $object, ?string $eventName = null)
    {
        parent::__construct($object, $eventName == null ? $this::class : $eventName);
    }

    public function getEmailConfig(): EmailSenderConfiguration
    {
        /** @var EmailSenderConfiguration */
        $emailconfig = Container::getInstance()->make(EmailSenderConfiguration::class);
        $emailconfig->setSubject('Password Recovery!');
        $emailconfig->setCssPath(ASSET . 'css' . DS . 'custom' . DS . 'client' . DS . 'users' . DS . 'email' . DS . 'forgotPassword' . DS . 'forgotPawwordTemplate.css');
        $emailconfig->setEmailTemplate('users' . DS . 'emailTemplate' . DS . 'forgotPasswordEmailTemplate');
        return $emailconfig;
    }
}