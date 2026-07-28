<section class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-header">
            <h1>Restaurante</h1>
            <p>
                Inicia sesión para gestionar tus pedidos.
            </p>
        </div>

        <form method="post">

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
                    placeholder="Ingrese su contraseña"
                    required
                >
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
                Iniciar Sesión
            </button>

        </form>

        <div class="auth-footer">
            <span>¿No tienes cuenta?</span>
            <a href="index.php?page=Sec.Register">
                Crear cuenta
            </a>
        </div>

    </div>

</section>