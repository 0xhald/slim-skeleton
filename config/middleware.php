<?php

use Slim\App;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();
    // Add Slim built-in routing middleware
    $app->addRoutingMiddleware();
    // Handle exceptions
    $app->addErrorMiddleware(displayErrorDetails: true, logErrors: true, logErrorDetails: true);
};