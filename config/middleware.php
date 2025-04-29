<?php

use App\Middleware\ErrorHandlerMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Tuupola\Middleware\CorsMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();
    // Add Slim built-in routing middleware
    $app->addRoutingMiddleware();
    // Handle exceptions
    $app->add(ErrorMiddleware::class);
    $app->add(CorsMiddleware::class);
};