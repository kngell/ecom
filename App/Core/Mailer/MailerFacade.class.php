<?php

declare(strict_types=1);
class MailerFacade
{
    /** @var object */
    protected object $mailer;
    private ContainerInterface $container;

    public function __construct(?array $settings = null)
    {
        $this->mailer = $this->container->make(MailerFactory::class, [
            'settings' => $settings,
        ])->create(\PHPMailer\PHPMailer\PHPMailer::class);
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
    public function basicMail(string $subject, string $from, string $to, string $message): mixed
    {
        return $this->mailer
            ->subject($subject)
            ->from($from)
            ->address($to)
            ->body($message)
            ->send();
    }
}