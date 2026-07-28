<?php
 
namespace Controllers\Tracking;
 
use Controllers\PrivateController;
use Dao\Tracking\Pedidos as PedidosDAO;
use Dao\Tracking\Platos as PlatosDAO;
use Utilities\Security;
use Utilities\Site;
 
class Pedido extends PrivateController
{
    public function run(): void
    {
        $this->requireAuth(true);

        if (!$this->isPostBack()) {
            Site::redirectTo("index.php?page=Tracking_Menu");
            return;
        }
 
        $platoId  = intval($_POST["platoId"]  ?? 0);
        $cantidad = intval($_POST["cantidad"] ?? 0);
 
        $usuario_id = Security::getUserId();
        if ($usuario_id <= 0) {
            Site::redirectTo("index.php?page=Sec.Login");
            return;
        }

        // Evita crear pedidos sin plato o con cantidad cero/negativa.
        if ($platoId <= 0 || $cantidad <= 0) {
            Site::redirectTo(
                "index.php?page=Tracking_Menu",
                "Debe seleccionar un plato y una cantidad valida."
            );
            return;
        }
 
        $plato = PlatosDAO::getById($platoId);
 
        if (!$plato) {
            Site::redirectTo(
                "index.php?page=Tracking_Menu",
                "El plato seleccionado no existe."
            );
            return;
        }
 
        // La validacion de stock se repite en servidor por seguridad.
        if ((int)$plato["stock"] <= 0 || (int)$plato["stock"] < $cantidad) {
            Site::redirectTo(
                "index.php?page=Tracking_Menu",
                "No hay suficiente stock para realizar el pedido."
            );
            return;
        }
 
        PedidosDAO::insertPedido($usuario_id, $platoId, $cantidad);
        PlatosDAO::reducirStock($platoId, $cantidad);
 
        Site::redirectTo("index.php?page=Tracking_MisPedidos");
    }
}
