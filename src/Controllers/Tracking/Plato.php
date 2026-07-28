<?php

namespace Controllers\Tracking;

use Controllers\PublicController;
use Dao\Tracking\Platos as PlatoDAO;
use Utilities\Site;
use Views\Renderer;

const PLATO_LIST_URL = "index.php?page=Tracking_Menu";

class Plato extends PublicController
{
    private array $viewData = [];

    private int $platoId = 0;

    public function run(): void
    {
        $this->platoId = intval(
            $_GET["platoId"] ?? 0
        );

        if ($this->platoId <= 0) {
            Site::redirectToWithMsg(
                PLATO_LIST_URL,
                "Plato no especificado"
            );
            return;
        }

        $plato = PlatoDAO::getById(
            $this->platoId
        );

        if (!$plato) {
            Site::redirectToWithMsg(
                PLATO_LIST_URL,
                "Plato no encontrado"
            );
            return;
        }

        $this->viewData = $plato;

        Renderer::render(
            "tracking/plato",
            $this->viewData
        );
    }
}