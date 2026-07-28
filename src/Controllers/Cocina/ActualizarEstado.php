<?php

namespace Controllers\Cocina;

class ActualizarEstado extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(
            in_array(\Utilities\Security::getUserRole(), ['cocina', 'admin'], true)
        );

        $id      = (int) ($_POST['id']      ?? 0);
        $version = (int) ($_POST['version'] ?? 0);
        $estado  = $_POST['estado'] ?? '';
        $accion  = $_POST['accion'] ?? 'avanzar';

        if ($id <= 0 || $version <= 0) {
            \Utilities\Site::redirectTo('index.php?page=Cocina.Cocina&error=conflicto');
            return;
        }

        if ($accion === 'confirmar' && $estado === 'pendiente') {
            $ok = \Dao\PedidoDao::actualizarEstado($id, 'en_proceso', $version);
            \Utilities\Site::redirectTo(
                $ok
                    ? 'index.php?page=Cocina.ConfirmarPedido'
                    : 'index.php?page=Cocina.ConfirmarPedido&error=conflicto'
            );
            return;
        }

        if ($accion === 'rechazar' && $estado === 'pendiente') {
            $ok = \Dao\PedidoDao::actualizarEstado($id, 'cancelado', $version);
            if ($ok) {
                $items = \Dao\PedidoDao::getItems($id);
                foreach ($items as $item) {
                    \Dao\Tracking\Platos::aumentarStock(
                        (int)$item['plato_id'],
                        (int)$item['cantidad']
                    );
                }
            }
            \Utilities\Site::redirectTo(
                $ok
                    ? 'index.php?page=Cocina.ConfirmarPedido'
                    : 'index.php?page=Cocina.ConfirmarPedido&error=conflicto'
            );
            return;
        }

        $estados = ['pendiente', 'en_proceso', 'listo', 'entregado'];
        $idx = array_search($estado, $estados, true);

        if ($idx === false || $estado === 'entregado') {
            \Utilities\Site::redirectTo('index.php?page=Cocina.Cocina&error=conflicto');
            return;
        }

        $siguiente = $estados[$idx + 1] ?? 'entregado';
        $ok = \Dao\PedidoDao::actualizarEstado($id, $siguiente, $version);

        \Utilities\Site::redirectTo(
            $ok
                ? 'index.php?page=Cocina.Cocina'
                : 'index.php?page=Cocina.Cocina&error=conflicto'
        );
    }
}
