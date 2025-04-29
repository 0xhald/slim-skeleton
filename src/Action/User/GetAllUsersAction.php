<?php

namespace App\Action\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\User\Service\UserGetter;

final class GetAllUsersAction
{
    private UserGetter $service;

    public function __construct(UserGetter $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $users = $this->service->getAllUsers();
        $response->getBody()->write(\json_encode($users));
        return $response->withAddedHeader('Content-Type', 'application/json');
    }
}