<?php

namespace App\Action\Auth;

use App\Security\JwtAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class TokenCreateAction
{
    private JwtAuth $JwtAuth;

    public function __construct(JwtAuth $JwtAuth)
    {
        $this->JwtAuth = $JwtAuth;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // $data = (array)$request->getParsedBody();

        // $username = (string)($data['username'] ?? '');
        // $password = (string)($data['password'] ?? '');

        // Validate login (pseudo code)
        // Warning: This should be done in an Service and not here!
        // $userAuthData = $this->userAuth->authenticate($username, $password);

        // Create a fresh token
        $token = $this->JwtAuth->createJwt(
            [
                'uid' => 99999,
            ]
        );

        // Transform the result into a OAuh 2.0 Access Token Response
        // https://www.oauth.com/oauth2-servers/access-tokens/access-token-response/
        $result = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $this->JwtAuth->getLifetime(),
        ];

        // Build the HTTP response
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($result, JSON_THROW_ON_ERROR));

        return $response->withStatus(201);
    }
}