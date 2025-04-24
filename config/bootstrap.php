<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . "/../vendor/autoload.php";

// Build DI container instance
$container = (new ContainerBuilder())->addDefinitions(__DIR__ . "/container.php")->build();
return $container->get(App::class);