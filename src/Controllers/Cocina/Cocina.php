<?php
namespace Controllers\Cocina;

class Cocina extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(
            in_array(\Utilities\Security::getUserRole(), ['cocina', 'admin'], true)
        );

        $pedidos = \Dao\PedidoDao::getAll();

        foreach ($pedidos as &$pedido) {
            $pedido['accion_estado_texto'] = $pedido['estado'] === 'en_proceso'
                ? 'Marcar listo'
                : 'Marcar entregado';
        }
        unset($pedido);

        \Views\Renderer::render('cocina/cocina', ['pedidos' => $pedidos]);
    }
}
