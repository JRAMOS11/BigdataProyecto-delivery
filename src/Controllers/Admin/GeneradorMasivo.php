<?php

namespace Controllers\Admin;

class GeneradorMasivo extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(
            \Utilities\Security::getUserRole() === 'admin'
        );

        \Utilities\Site::addLink(
            'public/css/generador.css'
        );

        \Views\Renderer::render(
            'admin/generadormasivo',
            []
        );
    }
}