<?php
 
namespace Controllers\Tracking;
 
use Controllers\PrivateController;
use Dao\Tracking\Platos as PlatosDAO;
use Views\Renderer;
 
class Menu extends PrivateController
{
    private array $platos = [];
 
    public function run(): void
    {
        $this->requireAuth(true);
 
        $this->platos = PlatosDAO::getAll();
 
        foreach ($this->platos as &$plato) {
            $plato["platoDisponible"] = ($plato["stock"] > 0);
        }
        unset($plato);

        $carrito = $_SESSION['carrito'] ?? [];
        $cartCount = array_sum(array_map('intval', $carrito));
 
        Renderer::render(
            "tracking/menu",
            [
                "platos" => $this->platos,
                "cartCount" => $cartCount,
                "hasCart" => $cartCount > 0,
            ]
        );
    }
}
 
