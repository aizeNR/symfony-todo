<?php

namespace App\Subscriber;

use App\Event\User\CreateUserEvent;
use App\Services\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateUserEvent::NAME => 'onCreateUser',
        ];
    }

    public function onCreateUser(CreateUserEvent $event): void
    {
        $user = $event->getUser();

        try {
            $this->mailService->sendEmailToUser($user, 'test');
        } catch (TransportExceptionInterface $exception) {
            // TODO log
        }
    }
}