<?php

declare(strict_types=1);

class VerifyUserAccountEvent extends AbstractEmailSenderEvent implements EmailSenderEventInterface
{
    public function __construct(object $object, ?string $eventName = null)
    {
        parent::__construct($object, $eventName == null ? $this::class : $eventName);
    }

    /**
     * Get the value of emailConfig.
     */
    public function getEmailConfig() : EmailSenderConfiguration
    {
        /** @var EmailSenderConfiguration */
        $emailconfig = Container::getInstance()->make(EmailSenderConfiguration::class);
        $emailconfig->setSubject('Email Verification!');
        $emailconfig->setFrom('', 'K\'nGELL Ingenierie Logistique');
        return $emailconfig;
    }

    public function parse(string $html) : string
    {
        $html = str_replace('{{name}}', $this->getUserName(), $html);
        $html = str_replace('{{EmailVerifylink}}', $this->getLink(), $html);
        return $this->parseDom($html);
    }
}