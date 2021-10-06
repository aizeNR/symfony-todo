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
     *
     * @param string $emailType
     *
     * @return void
     */
    public function sendEmailToUser(User $user, string $emailType): void
    {
//        $email = $this->emailFactory->buildEmailByType($user, $emailType)
        $email = $this->getTestMail($user);

        $this->mailer->send($email);
    }

    /**
     * @param User $user
     * @return Email
     */
    private function getTestMail(User $user): Email
    {
        $email = $user->getEmail();

        if (is_null($email)) {
            throw new \DomainException('User doesnt have email!');
        }

        return (new Email())
            ->from('hello@example.com')
            ->to($email)
            ->subject('Test Mail')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
    }
}