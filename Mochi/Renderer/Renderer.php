<?php

namespace Mochi\Renderer;

use Psr\Http\Message\ResponseInterface as Response;
use Smarty;

final class Renderer
{
    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function json(Response $response, ?int $statusCode = 200, $data): Response
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode ?? 200);
    }

    public function smarty(Response $response, string $template, ?array $data = []): Response
    {
        $this->smarty->assign($data);
        $body = $response->getBody();
        $body->write($this->smarty->fetch($template));
        return $response->withHeader('Content-Type', 'text/html')->withBody($body);
    }
}
