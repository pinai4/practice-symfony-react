<?php

declare(strict_types=1);

namespace App\Security\OAuth\Server\EventSubscriber;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use League\Bundle\OAuth2ServerBundle\OAuth2Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthorizationRequestResolverSubscriber implements EventSubscriberInterface
{
    public const SESSION_AUTHORIZATION_RESULT = '_app.oauth2.authorization_result';

    public function __construct(
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OAuth2Events::AUTHORIZATION_REQUEST_RESOLVE => 'onAuthorizationRequestResolve',
        ];
    }

    public function onAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        if ($request->getSession()->has(self::SESSION_AUTHORIZATION_RESULT)) {
            $event->resolveAuthorization(
                (bool) $request->getSession()->get(self::SESSION_AUTHORIZATION_RESULT)
            );
            $request->getSession()->remove(self::SESSION_AUTHORIZATION_RESULT);
        } else {
            $url = $this->urlGenerator->generate('app_consent', $request->query->all());

            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }
}
