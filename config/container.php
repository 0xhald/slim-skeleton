<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use DI\Bridge\Slim\Bridge;

return [
    "settings" => fn() => require __DIR__ . "/settings.php",
    App::class => function (ContainerInterface $container) {
        $app = Bridge::create($container);
        // Register routes
        (require __DIR__ . "/routes.php")($app);
        // Register middleware
        (require __DIR__ . "/middleware.php")($app);
        return $app;
    }
];