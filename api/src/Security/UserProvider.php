<?php

declare(strict_types=1);

namespace App\Security;

use App\ReadModel\Auth\AuthView;
use App\ReadModel\Auth\UserFetcher;
use Doctrine\DBAL\Exception;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserFetcher $userFetcher;

    public function __construct(UserFetcher $userFetcher)
    {
        $this->userFetcher = $userFetcher;
    }


    /**
     * @throws Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        throw new \Exception('TODO: fill in loadUserByUsername() inside '.__FILE__);
    }

    /**
     * @throws Exception
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userFetcher->findForAuthByEmail($identifier);
        if(is_null($user)) {
            throw new UserNotFoundException();
        }

        return self::identityByUser($user);
    }

    private static function identityByUser(AuthView $user): UserIdentity
    {
        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password,
            $user->name,
            $user->role
        );
    }

}
