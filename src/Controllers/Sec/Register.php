<?php

namespace Controllers\Sec;

class Register extends \Controllers\PublicController
{
    private string $nombre = "";
    private string $email = "";
    private string $error = "";

    public function run(): void
    {
        if ($this->isPostBack()) {

            $this->nombre = trim($_POST["nombre"] ?? "");
            $this->email = trim($_POST["email"] ?? "");
            $passwd = trim($_POST["passwd"] ?? "");

            if (
                empty($this->nombre) ||
                empty($this->email) ||
                empty($passwd)
            ) {
                $this->error =
                    "Todos los campos son obligatorios.";

            } elseif (
                !filter_var(
                    $this->email,
                    FILTER_VALIDATE_EMAIL
                )
            ) {
                $this->error =
                    "Ingrese un correo electrónico válido.";

            } elseif (strlen($passwd) < 8) {

                $this->error =
                    "La contraseña debe tener mínimo 8 caracteres.";

            } elseif (!preg_match('/[A-Z]/', $passwd)) {

                $this->error =
                    "La contraseña debe incluir una letra mayúscula.";

            } elseif (!preg_match('/[a-z]/', $passwd)) {

                $this->error =
                    "La contraseña debe incluir una letra minúscula.";

            } elseif (!preg_match('/[0-9]/', $passwd)) {

                $this->error =
                    "La contraseña debe incluir un número.";

            } elseif (!preg_match('/[^A-Za-z0-9]/', $passwd)) {

                $this->error =
                    "La contraseña debe incluir un carácter especial.";

            } else {

                $existe =
                    \Dao\UsuarioDAO::getUsuarioByEmail(
                        $this->email
                    );

                if ($existe) {

                    $this->error =
                        "Ya existe una cuenta con ese correo.";

                } else {

                    \Dao\UsuarioDAO::crearUsuario(
                        $this->nombre,
                        $this->email,
                        $passwd
                    );

                    \Utilities\Site::redirectTo(
                        "index.php?page=Sec.Login"
                    );

                    return;
                }
            }
        }

        \Utilities\Site::addLink(
            "public/css/global.css"
        );

        \Views\Renderer::render(
            "security/register",
            get_object_vars($this)
        );
    }
}