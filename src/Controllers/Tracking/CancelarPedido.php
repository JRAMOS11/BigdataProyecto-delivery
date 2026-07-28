<?php

namespace Controllers\Tracking;

use Controllers\PublicController;
use Dao\Tracking\Pedidos as PedidosDAO;
use Dao\Tracking\Platos as PlatosDAO;
use Utilities\Site;

class CancelarPedido extends PublicController
{
    public function run(): void
    {
        if (!$this->isPostBack()) {
            Site::redirectTo("index.php?page=Tracking_MisPedidos");
            return;
        }

        $pedidoId = intval($_POST["pedidoId"] ?? 0);

        $pedido = PedidosDAO::getPedidoById($pedidoId);

        if (!$pedido || $pedido["estado"] !== "pendiente") {
            Site::redirectTo(
                "index.php?page=Tracking_MisPedidos",
                "No se puede cancelar este pedido."
            );
            return;
        }

        PedidosDAO::cancelarPedido($pedidoId);

        PlatosDAO::aumentarStock(
            $pedido["plato_id"],
            $pedido["cantidad"]
        );

        Site::redirectTo(
            "index.php?page=Tracking_MisPedidos",
            "Pedido cancelado correctamente."
        );
    }
}