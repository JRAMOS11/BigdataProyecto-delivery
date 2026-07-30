<?php

namespace Controllers\Admin;

class Estadisticas extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(
            \Utilities\Security::getUserRole() === 'admin'
        );

        \Utilities\Site::addLink(
            'public/css/estadisticas.css'
        );

        \Views\Renderer::render(
            'admin/estadisticas',
            []
        );
    }
}