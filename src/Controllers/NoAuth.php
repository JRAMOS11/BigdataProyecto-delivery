<?php

namespace Controllers;

class NoAuth extends PublicController
{
    public function run(): void
    {
        http_response_code(403);
        \Views\Renderer::render('noauth', []);
    }
}
