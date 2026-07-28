<?php

namespace Controllers\Tracking;

use Controllers\PrivateController;
use Dao\Tracking\Pedidos as PedidosDAO;
use Utilities\Security;
use Views\Renderer;

class MisPedidos extends PrivateController
{
    private array $pedidos = [];
    private array $estados = [
        "" => "Todos",
        "pendiente" => "Pendiente",
        "en_proceso" => "En Proceso",
        "listo" => "Listo",
        "entregado" => "Entregado",
        "cancelado" => "Cancelado",
    ];

    public function run(): void
    {
        $this->requireAuth(true);

        $usuario_id = Security::getUserId();
        $estadoFiltro = $_GET["estado"] ?? "";

        // Solo se aceptan estados conocidos para evitar filtros invalidos desde la URL.
        if (!array_key_exists($estadoFiltro, $this->estados)) {
            $estadoFiltro = "";
        }

        $this->pedidos = PedidosDAO::getPedidosByUser($usuario_id, $estadoFiltro);

        foreach ($this->pedidos as &$pedido) {
            $pedido["total"] = number_format(
                (float)$pedido["cantidad"] * (float)$pedido["precio"],
                2
            );
            $pedido["estadoDsc"] = $this->estados[$pedido["estado"]] ?? $pedido["estado"];
            $pedido["estadoClass"] = $this->getEstadoClass($pedido["estado"]);
            $pedido["puedeCancelar"] = $pedido["estado"] === "pendiente";
            // Estos pasos alimentan la linea visual de seguimiento del pedido.
            $pedido["pasoPendiente"] = $this->getPasoClass($pedido["estado"], "pendiente");
            $pedido["pasoProceso"] = $this->getPasoClass($pedido["estado"], "en_proceso");
            $pedido["pasoListo"] = $this->getPasoClass($pedido["estado"], "listo");
            $pedido["pasoEntregado"] = $this->getPasoClass($pedido["estado"], "entregado");
        }
        unset($pedido);

        $estadoOptions = [];
        foreach ($this->estados as $value => $label) {
            $estadoOptions[] = [
                "value" => $value,
                "label" => $label,
                "selected" => $value === $estadoFiltro ? "selected" : "",
            ];
        }

        Renderer::render(
            "tracking/mispedidos",
            [
                "pedidos" => $this->pedidos,
                "estadoOptions" => $estadoOptions,
                "estadoFiltroDsc" => $this->estados[$estadoFiltro],
                "hasPedidos" => count($this->pedidos) > 0,
            ]
        );
    }

    private function getEstadoClass(string $estado): string
    {
        return match ($estado) {
            "pendiente" => "estado-pendiente",
            "en_proceso" => "estado-proceso",
            "listo" => "estado-listo",
            "entregado" => "estado-entregado",
            "cancelado" => "estado-cancelado",
            default => "",
        };
    }

    private function getPasoClass(string $estado, string $paso): string
    {
        if ($estado === "cancelado") {
            return "tracking-step muted";
        }

        $orden = [
            "pendiente" => 1,
            "en_proceso" => 2,
            "listo" => 3,
            "entregado" => 4,
        ];

        $estadoActual = $orden[$estado] ?? 0;
        $pasoActual = $orden[$paso] ?? 0;

        if ($estadoActual === $pasoActual) {
            return "tracking-step active";
        }

        return $estadoActual > $pasoActual ? "tracking-step done" : "tracking-step";
    }
}
