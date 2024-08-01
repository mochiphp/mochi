<?php

namespace Mochi\Renderer;

use Psr\Http\Message\ResponseInterface as Response;

final class JsonRenderer
{
    public function json(Response $response, $data): Response
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
