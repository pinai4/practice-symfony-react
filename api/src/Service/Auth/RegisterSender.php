<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Model\Auth\Entity\User\Email;
use App\Model\Auth\Service\RegisterSenderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class RegisterSender implements RegisterSenderInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Email $emailAddress): void
    {
        $email = (new TemplatedEmail())
            ->to($emailAddress->getValue())
            ->htmlTemplate('auth/emails/register.html.twig')
            ->context([
                          'frontend_url' => 'http://localhost:8080',
                      ]);

        $this->mailer->send($email);
    }
}
