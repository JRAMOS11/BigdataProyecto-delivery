<?php
namespace Controllers\Admin;

class GestionPlatos extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(\Utilities\Security::getUserRole() === 'admin');

        $mensaje = '';

        if ($this->isPostBack()) {
            $accion = $_POST['accion'] ?? '';
            $id     = (int)($_POST['id'] ?? 0);

            if ($accion === 'toggle' && $id > 0) {
                $disponible = (int)($_POST['disponible'] ?? 0);
                \Dao\AdminDao::toggleDisponible($id, $disponible);
                $mensaje = 'Disponibilidad actualizada.';
            } elseif ($accion === 'stock' && $id > 0) {
                $stock = (int)($_POST['stock'] ?? 0);
                \Dao\AdminDao::actualizarStock($id, $stock);
                $mensaje = 'Stock actualizado.';
            } elseif ($accion === 'crear') {
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $precio = (float)($_POST['precio'] ?? 0);
                $stock = (int)($_POST['stock'] ?? 0);
                $disponible = (int)($_POST['disponible'] ?? 1);

                if ($nombre !== '' && $precio >= 0 && $stock >= 0) {
                    \Dao\AdminDao::crearPlato($nombre, $descripcion, $precio, $stock, $disponible);
                    $mensaje = 'Plato agregado al menu.';
                } else {
                    $mensaje = 'Completa nombre, precio y stock validos.';
                }
            } elseif ($accion === 'editar' && $id > 0) {
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $precio = (float)($_POST['precio'] ?? 0);
                $stock = (int)($_POST['stock'] ?? 0);
                $disponible = (int)($_POST['disponible'] ?? 0);

                if ($nombre !== '' && $precio >= 0 && $stock >= 0) {
                    \Dao\AdminDao::actualizarPlato($id, $nombre, $descripcion, $precio, $stock, $disponible);
                    $mensaje = 'Plato actualizado.';
                } else {
                    $mensaje = 'No se pudo actualizar: revisa los datos.';
                }
            }
        }

        $platos = \Dao\AdminDao::getAllPlatos();

        foreach ($platos as &$plato) {
            $plato['disponible_texto']     = $plato['disponible'] ? 'si' : 'no';
            $plato['disponible_btn_texto'] = $plato['disponible'] ? 'Deshabilitar' : 'Habilitar';
            $plato['disponible_btn_clase'] = $plato['disponible'] ? '' : 'btn-success';
        }
        unset($plato);

        \Utilities\Site::addLink('public/css/admin.css');
        \Views\Renderer::render('admin/platos', ['platos' => $platos, 'mensaje' => $mensaje]);
    }
}
