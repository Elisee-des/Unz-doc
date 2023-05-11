<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\TwigUserRuntime;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigUserExtension extends AbstractExtension
{

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('activeTwigUser', [$this, 'activeTwigUser']),
        ];
    }

    /**
     * Pass route names. If one of route names matches current route, this function returns
     * 'active'
     * @param array $routesToCheck
     * @return string
     */
    public function activeTwigUser(array $routesToCheck)
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
