<section class="plato-detalle">

    <h1>Detalle del Plato</h1>

    <div class="detalle-card">

        <div class="detalle-row">
            <strong>ID:</strong>
            {{id}}
        </div>

        <div class="detalle-row">
            <strong>Nombre:</strong>
            {{nombre}}
        </div>

        <div class="detalle-row">
            <strong>Descripción:</strong>
            {{descripcion}}
        </div>

        <div class="detalle-row">
            <strong>Precio:</strong>
            $ {{precio}}
        </div>

        <div class="detalle-row">
            <strong>Stock:</strong>
            {{stock}}
        </div>

        <div class="detalle-row">
            <strong>Disponible:</strong>
            {{disponible}}
        </div>

        <button
            type="button"
            id="btnRegresar"
            class="caps-secondary-btn"
        >
            Regresar
        </button>

    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("btnRegresar")
        .addEventListener("click", () => {
            window.location.assign(
                "index.php?page=Tracking_Menu"
            );
        });
});
</script>