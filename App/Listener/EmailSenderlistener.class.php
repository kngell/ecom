<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EmailSenderlistener extends AbstractEmailSenderListener implements ListenerInterface
{
    private MailerFacade $mailerFacade;
    // private CssToInlineStyles $inlineCssClass

    public function __construct(protected View $view, protected CssToInlineStyles $inlineCssClass)
    {
        $this->mailerFacade = Container::getInstance()->make(MailerFacade::class, [
            'settings' => [
                'SMTPDebug' => 0,
                'SMTPAuth' => true,
                'SMTPSecure' => PHPMailer::ENCRYPTION_SMTPS,
                'Username' => SMTP_USERNAME,
                'Password' => SMTP_PASSWORD,
                'Host' => SMTP_HOST,
                'Port' => SMTP_PORT,
            ],
        ]);
    }

    public function handle(Object $event) : iterable
    {
        /** @var EmailSenderConfiguration */
        $emailConfig = $event->getEmailConfig();
        $mail = $this->mailerFacade->basicMail($emailConfig->getSubject(), $emailConfig->getFrom(), $event->getObject()->getEmail(), $this->getMessage($emailConfig));
        return [$mail];
    }
}