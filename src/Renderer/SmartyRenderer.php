<?php

namespace Mochi\Renderer;

use Psr\Http\Message\ResponseInterface as Response;
use Smarty;

final class SmartyRenderer
{
    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function render(Response $response, string $template, ?array $data = []): Response
    {
        $this->smarty->assign($data);
        $body = $response->getBody();
        $body->write($this->smarty->fetch($template));
        return $response->withHeader('Content-Type', 'text/html')->withBody($body);
    }
}
