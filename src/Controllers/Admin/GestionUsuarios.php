<?php
namespace Controllers\Admin;
 
class GestionUsuarios extends \Controllers\PrivateController
{
    public function run(): void
    {
        $this->requireAuth(\Utilities\Security::getUserRole() === 'admin');
 
        $mensaje = '';
 
        if ($this->isPostBack()) {
            $accion = $_POST['accion'] ?? '';
            $id     = (int)($_POST['id'] ?? 0);
 
            if ($accion === 'cambiar_rol' && $id > 0) {
                $rol = $_POST['rol'] ?? '';
                if (in_array($rol, ['cliente', 'cocina', 'admin'], true)) {
                    \Dao\AdminDao::cambiarRol($id, $rol);
                    $mensaje = 'Rol actualizado correctamente.';
                }
            } elseif ($accion === 'eliminar' && $id > 0) {
                \Dao\AdminDao::eliminarUsuario($id);
                $mensaje = 'Usuario eliminado.';
            }
        }
 
        $usuarios = \Dao\AdminDao::getAllUsuarios();
        \Utilities\Site::addLink('public/css/admin.css');
        \Views\Renderer::render('admin/usuarios', ['usuarios' => $usuarios, 'mensaje' => $mensaje]);
    }
}
 