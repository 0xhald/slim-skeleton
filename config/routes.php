<?php

use Slim\App;

return function (App $app) {
    $app->get('/', \App\Action\User\GetAllUsersAction::class);
};