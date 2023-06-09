<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $numero = $request->request->get('numero', '');

        $request->getSession()->set(Security::LAST_USERNAME, $numero);

        return new Passport(
            new UserBadge($numero),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $roles = $token->getUser()->getRoles();
        if (in_array("ROLE_ADMIN", $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }
        elseif (in_array("ROLE_SUPER_ADMIN", $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }
        elseif (in_array("ROLE_EDITEUR", $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        }
        elseif (in_array("ROLE_BANI", $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_main_page_principal'));
        }
        else {
            return new RedirectResponse($this->urlGenerator->generate('app_user_dashboard'));
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('app_main_page_principal'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
