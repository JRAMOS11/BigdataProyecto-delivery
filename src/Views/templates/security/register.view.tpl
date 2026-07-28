<section class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-header">
            <h1>Crear Cuenta</h1>
            <p>
                Regístrate para realizar pedidos en línea.
            </p>
        </div>

        <form method="post">

            <div class="auth-group">
                <label for="nombre">
                    Nombre Completo
                </label>

                <input
                    id="nombre"
                    type="text"
                    name="nombre"
                    value="{{nombre}}"
                    placeholder="Ingrese su nombre"
                    minlength="3"
                    maxlength="80"
                    required
                >
            </div>

            <div class="auth-group">
                <label for="email">
                    Correo Electrónico
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{email}}"
                    placeholder="correo@ejemplo.com"
                    maxlength="100"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="auth-group">
                <label for="passwd">
                    Contraseña
                </label>

                <input
                 id="passwd"
                 type="password"
                 name="passwd"
                 placeholder="Mínimo 8 caracteres"
                 minlength="8"
                 
                 
                  required
                >

                <small>
                    Debe tener mínimo 8 caracteres, una mayúscula,
                    una minúscula, un número y un carácter especial.
                </small>
            </div>

            {{if error}}
            <div class="auth-error">
                {{error}}
            </div>
            {{endif error}}

            <button
                type="submit"
                class="auth-btn"
            >
                Crear Cuenta
            </button>

        </form>

        <div class="auth-footer">
            <span>¿Ya tienes cuenta?</span>
            <a href="index.php?page=Sec.Login">
                Iniciar sesión
            </a>
        </div>

    </div>

</section>