<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class TwigAdminRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function doSomething($value)
    {
        // ...
    }
}
