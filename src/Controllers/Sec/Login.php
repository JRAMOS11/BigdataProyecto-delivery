<?php

namespace Controllers\Sec;

class Login extends \Controllers\PublicController
{
    private string $email = "";
    private string $error = "";

    public function run(): void
    {
        if ($this->isPostBack()) {

            $this->email =
                trim($_POST["email"] ?? "");

            $passwd =
                trim($_POST["passwd"] ?? "");

            if (
                empty($this->email) ||
                empty($passwd)
            ) {

                $this->error =
                    "Debe ingresar correo y contraseña.";

            } elseif (
                !filter_var(
                    $this->email,
                    FILTER_VALIDATE_EMAIL
                )
            ) {

                $this->error =
                    "Ingrese un correo electrónico válido.";

            } else {

                $usuario =
                    \Dao\UsuarioDAO::getUsuarioByEmail(
                        $this->email
                    );

                if (
                    $usuario &&
                    password_verify(
                        $passwd,
                        $usuario["password"]
                    )
                ) {

                    session_regenerate_id(true);

                    \Utilities\Security::login(
                        (int) $usuario["id"],
                        $usuario["nombre"],
                        $usuario["email"],
                        $usuario["rol"]
                    );

                    \Utilities\Site::redirectTo(
                        "index.php"
                    );

                    return;

                } else {

                    $this->error =
                        "Correo o contraseña incorrectos.";
                }
            }
        }

        \Utilities\Site::addLink(
            "public/css/global.css"
        );

        \Views\Renderer::render(
            "security/login",
            get_object_vars($this)
        );
    }
}