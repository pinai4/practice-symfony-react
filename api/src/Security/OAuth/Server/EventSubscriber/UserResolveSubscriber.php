<?php

declare(strict_types=1);

namespace App\Security\OAuth\Server\EventSubscriber;

use App\Model\Auth\Service\PasswordHasher;
use App\Security\UserIdentity;
use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserResolveSubscriber implements EventSubscriberInterface
{
    private UserProviderInterface $userProvider;
    private PasswordHasher $passwordHasher;

    public function __construct(UserProviderInterface $userProvider, PasswordHasher $passwordHasher)
    {
        $this->userProvider = $userProvider;
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::USER_RESOLVE => 'onUserResolve',
        ];
    }

    public function onUserResolve(UserResolveEvent $event): void
    {
        /** @var UserIdentity $user */
        $user = $this->userProvider->loadUserByIdentifier($event->getUsername());

        if (!$this->passwordHasher->verify($user->getPassword(), $event->getPassword())) {
            return;
        }

        $event->setUser($user);
    }
}
