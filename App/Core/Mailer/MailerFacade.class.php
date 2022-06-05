<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

class MailerFacade
{
    /** @var object */
    protected object $mailer;

    public function __construct(?array $settings)
    {
        $this->mailer = Container::getInstance()->make(MailerFactory::class, [
            'settings' => $settings,
        ])->create(PHPMailer::class);
    }

    /**
     * Send Basic Email.
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $message
     * @return mixed
     * @throws Exception\MailerException
     */
    public function basicMail(string $subject, array $from, string $to, string $message): mixed
    {
        return $this->mailer
            ->subject($subject)
            ->from($from)
            ->address($to)
            ->body($message)
            ->send();
    }

    public function charset(?string $hset = null) : self
    {
        if (null !== $hset) {
            $this->mailer->charset($hset);
        }
        return $this;
    }
}