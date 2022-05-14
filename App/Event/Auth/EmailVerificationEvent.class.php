<?php

declare(strict_types=1);

class EmailVerificationEvent extends Event implements EmailSenderEventInterface
{
    private string $link;

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

    /**
     * Get the value of link.
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set the value of link.
     *
     * @return  self
     */
    public function setLink(string $link) : self
    {
        $this->link = $link;
        return $this;
    }
}