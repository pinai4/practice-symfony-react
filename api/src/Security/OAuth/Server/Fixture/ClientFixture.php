<?php

declare(strict_types=1);

namespace App\Security\OAuth\Server\Fixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\Model\Grant;
use League\Bundle\OAuth2ServerBundle\Model\RedirectUri;
use League\Bundle\OAuth2ServerBundle\Model\Scope;
use League\Bundle\OAuth2ServerBundle\OAuth2Grants;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new Client('Main App Frontend', 'frontend', null);

        $client->setGrants(
            new Grant(OAuth2Grants::AUTHORIZATION_CODE),
            new Grant(OAuth2Grants::PASSWORD),
            new Grant(OAuth2Grants::REFRESH_TOKEN),
        );

        $client->setScopes(new Scope('common'));
        $client->setRedirectUris(new RedirectUri('http://localhost:8080/oauth'));

        $manager->persist($client);

        $manager->flush();
    }
}
