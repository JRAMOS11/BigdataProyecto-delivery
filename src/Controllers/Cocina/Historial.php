<?php
namespace Controllers\Cocina;
 
class Historial extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(
            in_array(\Utilities\Security::getUserRole(), ['cocina', 'admin'], true)
        );
 
        $pedidos = \Dao\PedidoDao::getHistorial();
 
        foreach ($pedidos as &$pedido) {
            switch ($pedido['estado']) {
                case 'entregado':
                    $pedido['estadoDsc']   = 'Entregado';
                    $pedido['estadoClass'] = 'estado-entregado';
                    break;
                case 'cancelado':
                    $pedido['estadoDsc']   = 'Cancelado';
                    $pedido['estadoClass'] = 'estado-cancelado';
                    break;
                default:
                    $pedido['estadoDsc']   = $pedido['estado'];
                    $pedido['estadoClass'] = '';
            }
        }
        unset($pedido);
 
        \Views\Renderer::render('cocina/historial', ['pedidos' => $pedidos]);
    }
}
 