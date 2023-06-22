<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\OAuth\Server\EventSubscriber\AuthorizationRequestResolverSubscriber;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/consent', name: 'app_consent')]
    public function consent(Request $request): Response
    {
        if(!$this->isValidOAuth2AuthorizationRequestParams($request)) {
            throw new BadRequestException('Required params not found');
        }

        if ($request->isMethod("POST") && ($request->request->has('allow') || $request->request->has('deny'))) {
            switch (true) {
                case $request->request->has('allow'):
                    $request->getSession()->set(
                        AuthorizationRequestResolverSubscriber::SESSION_AUTHORIZATION_RESULT,
                        true
                    );
                    break;
                case $request->request->has('deny'):
                    $request->getSession()->set(
                        AuthorizationRequestResolverSubscriber::SESSION_AUTHORIZATION_RESULT,
                        false
                    );
                    break;
            }

            return $this->redirectToRoute('oauth2_authorize', $request->query->all());
        }

        return $this->render('login/authorization.html.twig');
    }

    public function isValidOAuth2AuthorizationRequestParams(Request $request): bool
    {
        return ($request->query->get('response_type')
            && $request->query->get('client_id')
            && $request->query->get('code_challenge')
            && $request->query->get('code_challenge_method'));
    }
}