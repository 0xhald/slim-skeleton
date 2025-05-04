<?php

use Slim\App;

return function (App $app) {
    $app->get('/', \App\Action\Home\HomeAction::class);
    $app->get('/tokens', \App\Action\Auth\TokenCreateAction::class);
};