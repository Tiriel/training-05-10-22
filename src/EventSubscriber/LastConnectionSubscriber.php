<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LastConnectionSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user instanceof User) {
            return;
        }

        $user->setLastConnectedAt(new \DateTimeImmutable());
        $this->repository->add($user, true);
    }
}