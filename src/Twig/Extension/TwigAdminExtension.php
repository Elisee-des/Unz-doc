<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\TwigAdminRuntime;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigAdminExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('activeTwigAdmin', [$this, 'activeTwigAdmin']),
        ];
    }

    /**
     * Pass route names. If one of route names matches current route, this function returns
     * 'active'
     * @param array $routesToCheck
     * @return string
     */
    public function activeTwigAdmin(array $routesToCheck)
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->get('_route');

        foreach ($routesToCheck as $routeToCheck) {
            if ($routeToCheck == $currentRoute) {
                return 'active';
            }
        }

        return '';
    }
}
