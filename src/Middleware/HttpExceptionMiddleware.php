<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;

final class HttpExceptionMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $exception) {
            // Render error response

            // The HTTP code, e.g. 500
            $code = $exception->getCode();
            $response = $this->responseFactory->createResponse($code);

            // The response body
            $response->getBody()->write(sprintf('Error: %s', $exception->getMessage()));

            return $response;
        }
    }
}
