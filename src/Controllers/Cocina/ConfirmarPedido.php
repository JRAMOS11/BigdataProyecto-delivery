<?php
namespace Controllers\Cocina;
 
class ConfirmarPedido extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(
            in_array(\Utilities\Security::getUserRole(), ['cocina', 'admin'], true)
        );
 
        $pedidos = \Dao\PedidoDao::getPendientes();
 
        \Utilities\Site::addLink('public/css/cocina.css');
        \Utilities\Context::setContext('mainClass', 'main-full');
        \Views\Renderer::render('cocina/confirmar', ['pedidos' => $pedidos]);
    } 
}