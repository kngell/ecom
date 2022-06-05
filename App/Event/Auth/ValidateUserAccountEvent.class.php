<?php

declare(strict_types=1);

class ValidateUserAccountEvent extends AbstractEmailSenderEvent implements EmailSenderEventInterface
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
        $emailconfig->setSubject('Account Validation!')
            ->setFrom('', 'K\'nGELL Ingenierie Logistique')
            ->setEmailTemplate('users' . DS . 'emailTemplate' . DS . 'successTemplate');
        return $emailconfig;
    }

    public function parse(string $html) : string
    {
        $html = str_replace('{{preheadText}}', $this->getPreheadText(), $html);
        $html = str_replace('{{msgTitle}}', $this->getMsgTitle(), $html);
        $html = str_replace('{{name}}', 'Bonjour &nbsp;' . $this->getUserName() . ',', $html);
        $html = str_replace('{{msgBody}}', $this->getMsgBody(), $html);
        $html = str_replace('{{btnText}}', $this->getBtnText(), $html);
        $html = str_replace('{{msgEnd}}', $this->getMsgEnd(), $html);
        $html = str_replace('{{link}}', $this->getLink(), $html);
        return $this->parseDom($html);
    }
}