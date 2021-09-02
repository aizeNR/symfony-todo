<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

//    /**
//     * @var EmailFactory
//     */
//    private $emailFactory;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
//        $this->emailFactory = $emailFactory;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailToUser(User $user, $emailType)
    {
//        $email = $this->emailFactory->buildEmailByType($user, $emailType)
        $email = $this->getTestMail($user);

        $this->mailer->send($email);
    }

    private function getTestMail(User $user)
    {
        return (new Email())
            ->from('hello@example.com')
            ->to($user->getEmail())
            ->subject('Test Mail')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
    }
}